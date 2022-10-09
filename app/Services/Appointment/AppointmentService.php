<?php

namespace App\Services\Appointment;

use App\Models\Appointment\Appointment;
use App\Services\Base\BaseService;
use App\Services\Base\CrudService;
use App\Services\ServiceProvider\ServiceProviderService;
use Carbon\Carbon;

/**
 * Class AppointmentService.
 */
class AppointmentService
{
    protected $base;
    protected $crud;
    protected $provider;
    public function __construct(
        BaseService $base,
        CrudService $crud,
        ServiceProviderService $provider
    ){
        $this->base = $base;
        $this->crud = $crud;
        $this->provider = $provider;
    }

    public function appointment(): Appointment
    {
        return new Appointment();
    }

    public function appointmentWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->appointment()
            ->with('user', 'pet', 'service_provider', 'service_provider_category', 'appointment_type');
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

    public function createAppointmentForUser($request, $userId): array
    {
        $input = $request->all();
        $input['user_id'] = $userId;
        $appointment = $this->appointment()->create($input);
        // Send email to service provider if appointment was created
        if(!$appointment){
            return [
                'success' => false,
                'appointment' => null,
                'message' => 'Error creating appointment',
            ];
        }
        $emailData = [
            'name' => $appointment->service_provider->name,
            'email' => $appointment->service_provider->email,
            'user_name' => $appointment->user->name,
            'pet_type' => $appointment->pet->pet_type->name,
            'appointment_type' => $appointment->appointment_type->name,
            'appointment_note' => $appointment->note,
            'appointment_time' => Carbon::parse($appointment->appointment_time)
                ->format('g:i a, l jS F Y'),
        ];
        $this->base->sendEmail(
            $emailData,
            'emails.service-providers.new-appointment',
            'New Appointment | '.$emailData['user_name']
        );
        return [
            'success' => true,
            'appointment' => $appointment,
            'message' => 'Appointment successfully sent',
        ];
    }

    public function rescheduleAppointmentForUser($request, $id, $userId): array
    {
        $appointment = $this->appointmentById($id);
        $input = $request->all();
        if($appointment->user_id !== $userId){
            return [
                  'success' => false,
                  'appointment' => null,
                  'message' => 'Error rescheduling appointment',
            ];
        }
        $appointment->update($input);
        // Send email to service provider
        $emailData = [
            'name' => $appointment->service_provider->name,
            'email' => $appointment->service_provider->email,
            'user_name' => $appointment->user->name,
            'pet_type' => $appointment->pet->pet_type->name,
            'appointment_type' => $appointment->appointment_type->name,
            'appointment_note' => $appointment->note,
            'appointment_time' => Carbon::parse($appointment->appointment_time)
                ->format('g:i a, l jS F Y'),
        ];
        $this->base->sendEmail(
            $emailData,
            'emails.service-providers.new-appointment',
            'Appointment Reschedule | '.$emailData['user_name']
        );
        return [
            'success' => true,
            'appointment' => $appointment,
            'message' => 'Appointment rescheduled',
        ];
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

        $appointment->delete();

        return [
            'success' => true,
            'message' => 'Deleted',
        ];
    }
}
