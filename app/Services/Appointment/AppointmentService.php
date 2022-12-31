<?php

namespace App\Services\Appointment;

use App\Http\Resources\ServiceProvider\Appointment\ServiceProviderAppointmentResource;
use App\Http\Resources\User\Appointment\UserAppointmentResource;
use App\Models\Appointment\Appointment;
use App\Services\Base\BaseService;
use App\Services\Base\CrudService;
use App\Services\ServiceProvider\ServiceProviderService;
use App\Services\Wallet\WalletService;
use Carbon\Carbon;

/**
 * Class AppointmentService.
 */
class AppointmentService
{
    protected BaseService $base;
    protected CrudService $crud;
    protected ServiceProviderService $provider;
    protected WalletService $wallet;
    protected AppointmentTransactionService $transaction;
    public function __construct(
        BaseService $base,
        CrudService $crud,
        ServiceProviderService $provider,
        WalletService $wallet,
        AppointmentTransactionService $transaction
    ){
        $this->base = $base;
        $this->crud = $crud;
        $this->provider = $provider;
        $this->wallet = $wallet;
        $this->transaction = $transaction;
    }

    public function appointment(): Appointment
    {
        return new Appointment();
    }

    public function appointmentWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->appointment()
            ->with('user', 'pet', 'service_provider', 'service_provider_category', 'appointment_type', 'appointment_services');
    }

    public function appointmentById($id){
        return $this->appointmentWithRelations()->findOrFail($id);
    }

    public function appointmentsByUserId($userId): \Illuminate\Database\Eloquent\Builder
    {
        return $this->appointmentWithRelations()->where('user_id', $userId);
    }

    public function appointmentsByServiceProviderId($providerId): \Illuminate\Database\Eloquent\Builder
    {
        return $this->appointmentWithRelations()->where('service_provider_id', $providerId);
    }

    public function appointmentsApprovedServiceProvider($providerId): \Illuminate\Database\Eloquent\Builder
    {
        return $this->appointmentWithRelations()->where([
            ['service_provider_id', $providerId],
            ['status', 'approved'],
        ]);
    }

    public function appointmentService(): \App\Models\Appointment\AppointmentService
    {
        return new \App\Models\Appointment\AppointmentService();
    }

    public function servicesByAppointmentId($id){
       return $this->appointmentService()->where('appointment_id', $id);
    }

    public function appointmentServiceById($id){
        return $this->appointmentService()->findOrFail($id);
    }

    //Generate email verification token
    public function generateReference($length = 8): string
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = 'APT';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function createAppointmentForUser($request, $userId): array
    {
        // Request all except services because it's an array
        $input = $request->all();
        $input['user_id'] = $userId;
        $input['reference'] = $this->generateReference();

        // Services should be a multidimensional array with id and cost
        if(!is_array($request->services)){
            return [
                'success' => false,
                'appointment' => null,
                'message' => 'Services must be an array',
            ];
        }

        // Iterate services and get costs
        $serviceCosts = $this->getCostFromServices($request, $userId);
        if(!$serviceCosts){
            return [
                'success' => false,
                'appointment' => null,
                'message' => 'Insufficient funds, please fund wallet.',
            ];
        }
        $input['total_cost'] = $serviceCosts;

        $appointment = $this->appointment()->create($input);
        // Send email to service provider if appointment was created
        if(!$appointment){
            return [
                'success' => false,
                'message' => 'Error creating appointment',
            ];
        }

        $this->addAppointmentServices($request, $appointment);
        $this->addPaymentToTransaction($appointment);
        $this->sendEmailToServiceProvider($appointment);
        return [
            'success' => true,
            'appointment' => new UserAppointmentResource($appointment),
            'message' => 'Appointment successfully sent',
        ];
    }

    public function rescheduleAppointmentForUser($request, $id, $userId): array
    {
        $appointment = $this->appointmentById($id);
        $input = $request->all();

        // Services should be a multidimensional array with id and cost
        if(!is_array($request->services)){
            return [
                'success' => false,
                'appointment' => null,
                'message' => 'Services must be an array',
            ];
        }

        // Iterate services and get costs
        $serviceCosts = $this->getCostFromServices($request, $userId);
        if(!$serviceCosts){
            return [
                'success' => false,
                'message' => 'Insufficient funds, please fund wallet.',
            ];
        }

        if($appointment->user_id !== $userId){
            return [
              'success' => false,
              'appointment' => null,
              'message' => 'Error rescheduling appointment',
            ];
        }
        $input['total_cost'] = $serviceCosts;
        $input['status'] = 'pending';
        $appointment->update($input);
        $this->sendEmailToServiceProvider($appointment);
        return [
            'success' => true,
            'appointment' => $appointment,
            'message' => 'Appointment rescheduled',
        ];
    }

    protected function sendEmailToServiceProvider($appointment): void
    {
        $emailData = [
            'reference' => $appointment->reference,
            'name' => $appointment->service_provider->name,
            'email' => $appointment->service_provider->email,
            'user_name' => $appointment->user->name,
            'pet_type' => $appointment->pet->pet_type->name ?? '',
            'service_provider_category' => $appointment->service_provider_category->name ?? '',
            'appointment_note' => $appointment->note,
            'appointment_time' => Carbon::parse($appointment->appointment_time)
                ->format('g:i a, l jS F Y'),
        ];
        $this->base->sendEmail(
            $emailData,
            'emails.appointments.new-appointment',
            'New Appointment | '.$emailData['user_name']
        );
    }

    protected function getCostFromServices($request, $userId){
        $serviceCosts = 0;
        // Iterate services and get total cost
        foreach ($request->services as $value) {
            $serviceCosts += $value['cost'];
        }
        // if service costs is more than wallet balance return false
        if($serviceCosts > $this->wallet->walletByUserId($userId)->amount){
            return false;
        }
        return $serviceCosts;
    }

    protected function addAppointmentServices($request, $appointment): void
    {
        foreach ($request->services as $value){
            $this->appointmentService()->create([
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'service_provider_id' => $appointment->service_provider_id,
                'service_provider_service_id' => $value['id'],
            ]);
        }
    }

    public function deleteAppointmentForUser($id, $userId): array
    {
        $appointment = $this->appointmentById($id);
        if($appointment->user_id !== $userId){
            return [
                'success' => false,
                'message' => 'Error deleting appointment',
            ];
        }
        if($appointment->status === 1){
            return [
                'success' => false,
                'message' => 'Appointment already accepted',
            ];
        }

        // Delete appointment services before deleting appointments
        if($appointment->appointment_services && count($appointment->appointment_services) > 0) {
            foreach($appointment->appointment_services as $service){
                $service->delete();
            }
        }
        $appointment->delete();

        return [
            'success' => true,
            'message' => 'Deleted',
        ];
    }

    public function addServiceToAppointment($serviceId, $appointmentId): array
    {
        $appointment = $this->appointmentById($appointmentId);
        if(!$appointment){
            return [
                'success' => false,
                'message' => 'The appointment does not exist',
            ];
        }
        if($appointment->status === 1){
            return [
                'success' => false,
                'message' => 'Appointment already accepted',
            ];
        }

        $appointmentService = $this->appointmentService()->create([
           'appointment_id' => $appointmentId,
           'user_id' => $appointment->user_id,
           'service_provider_id' => $appointment->service_provider_id,
           'service_provider_service_id' => $serviceId,
        ]);

        $appointment->total_cost += $appointmentService->service->cost;
        $appointment->save();

        return [
            'success' => true,
            'message' => 'Service has been added',
        ];
    }

    public function removeServiceFromAppointment($serviceId, $appointmentId): array
    {
        $service = $this->appointmentServiceById($serviceId);
        $appointment = $this->appointmentById($appointmentId);

        if(!$service || !$appointment){
            return [
                'success' => false,
                'message' => 'The service or appointment does not exist',
            ];
        }

        if($appointment->status === 1){
            return [
                'success' => false,
                'message' => 'Appointment already accepted',
            ];
        }

        $appointment->total_cost -= $service->cost;
        $appointment->save();

        $service->delete();

        return [
            'success' => true,
            'message' => 'Service has been deleted',
        ];
    }

    public function addPaymentToTransaction($appointment){
        $this->transaction->appointmentTransaction()->create([
           'appointment_id' => $appointment->id,
           'user_id' => $appointment->user_id,
           'service_provider_id' => $appointment->service_provider_id,
           'amount' => $appointment->total_cost
        ]);

        $wallet = $this->wallet->walletByUserId($appointment->user_id);
        $wallet->amount -= $appointment->total_cost;
        $wallet->save();
    }

    public function serviceProviderAcceptAppointment($appointmentId, $providerId): array
    {
        $appointment = $this->appointmentById($appointmentId);
        if($appointment->service_provider_id !== $providerId){
            return [
                'success' => false,
                'message' => 'Unauthorized User',
            ];
        }
        if($appointment->status === 1){
            return [
                'success' => false,
                'message' => 'Appointment has already been accepted',
            ];
        }
        if($appointment->status === 2){
            return [
                'success' => false,
                'message' => 'Appointment was declined',
            ];
        }

        $appointment->status = 1;
        $appointment->save();

        $this->sendAcceptanceEmailToUser($appointment);

        return [
            'success' => true,
            'appointment' => new ServiceProviderAppointmentResource($appointment),
            'message' => 'Appointment accepted'
        ];
    }

    protected function sendAcceptanceEmailToUser($appointment): void
    {
        $emailData = [
            'reference' => $appointment->reference,
            'service_provider_name' => $appointment->service_provider->name,
            'name' => $appointment->user->name,
            'email' => $appointment->user->email,
            'pet_type' => $appointment->pet->pet_type->name ?? '',
            'service_provider_category' => $appointment->service_provider_category->name ?? '',
            'appointment_note' => $appointment->note,
            'appointment_time' => Carbon::parse($appointment->appointment_time)
                ->format('g:i a, l jS F Y'),
        ];
        $this->base->sendEmail(
            $emailData,
            'emails.appointments.accepted-appointment',
            'Accepted Appointment | '.$emailData['service_provider_name']
        );
    }

    public function serviceProviderRejectAppointment($appointmentId, $providerId): array
    {
        $appointment = $this->appointmentById($appointmentId);
        if($appointment->service_provider_id !== $providerId){
            return [
                'success' => false,
                'message' => 'Unauthorized User',
            ];
        }

        if($appointment->status === 1){
            return [
                'success' => false,
                'message' => 'Appointment has already been accepted',
            ];
        }

        // Update appointment
        $appointment->status = 2;
        $appointment->save();

        // Delete from transaction
        $transaction = $this->transaction->transactionByAppointmentId($appointment->id);
        if($transaction){
            $transaction->delete();
        }

        // Update user wallet
        $wallet = $this->wallet->walletByUserId($appointment->user_id);
        $wallet->amount += $appointment->total_cost;
        $wallet->save();

        return [
            'success' => true,
            'message' => "Appointment Declined",
        ];
    }

    public function serviceProviderCompletedAppointment($appointmentId, $providerId){

        $appointment = $this->appointmentById($appointmentId);
        if($appointment->service_provider_id !== $providerId){
            return [
                'success' => false,
                'message' => 'Unauthorized User',
            ];
        }

        if($appointment->status !== 1){
            return [
                'success' => false,
                'message' => 'Appointment has not been accepted',
            ];
        }

        // If this appointment date is not due, prevent completion.
        // Else proceed with completion
        $chosen_date = new Carbon($appointment->appointment_time);
        $now = Carbon::now();
        $now->addMinutes(30);

        if($chosen_date->gt($now)) {
            return [
                'success' => false,
                'message' => 'This appointment has not started',
            ];
        }

        $appointment->service_provider_completed = 1;
        $appointment->save();

        $transition = $this->transaction->transactionByAppointmentId($appointment->id);
        if($transition){
            $transition->status = 1;
            $transition->save();
        }

        $this->sendCompletionEmailToUser($appointment);

        return [
            'success' => true,
            'message' => "Congratulations, you completed this appointment",
        ];
    }

    protected function sendCompletionEmailToUser($appointment): void
    {
        $emailData = [
            'reference' => $appointment->reference,
            'service_provider_name' => $appointment->service_provider->name,
            'name' => $appointment->user->name,
            'email' => $appointment->user->email,
            'pet_type' => $appointment->pet->pet_type->name ?? '',
            'service_provider_category' => $appointment->service_provider_category->name ?? '',
            'appointment_note' => $appointment->note,
            'appointment_time' => Carbon::parse($appointment->appointment_time)
                ->format('g:i a, l jS F Y'),
        ];

        $this->base->sendEmail(
            $emailData,
            'emails.appointments.completed-appointment',
            'Completed Appointment | '.$emailData['service_provider_name']
        );
    }

}
