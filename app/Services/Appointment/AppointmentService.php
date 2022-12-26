<?php

namespace App\Services\Appointment;

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
    public function __construct(
        BaseService $base,
        CrudService $crud,
        ServiceProviderService $provider,
        WalletService $wallet
    ){
        $this->base = $base;
        $this->crud = $crud;
        $this->provider = $provider;
        $this->wallet = $wallet;
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

    public function createAppointmentForUser($request, $userId): array
    {
        // Request all except services because it's an array
        $input = $request->all();
        $input['user_id'] = $userId;

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
                'appointment' => null,
                'message' => 'Error creating appointment',
            ];
        }
        $this->addAppointmentServices($request, $appointment);
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
                'appointment' => null,
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
            'emails.service-providers.new-appointment',
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

//    public function acceptAppointment($appointmentId, $userId){
//        $appointment = $this->appointmentById($appointmentId);
//        if($appointment->service_provider_id !== $userId){
//            return [
//                'success' => false,
//                'message' => 'Incorrect user',
//            ];
//        }
//
//        $appointment->status = 1;
//        $appointment->save();
//
//    }

}
