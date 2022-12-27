<?php

namespace App\Services\Appointment;

use App\Models\Appointment\AppointmentTransaction;

/**
 * Class AppointmentTransactionService.
 */
class AppointmentTransactionService
{
    public function appointmentTransaction(): AppointmentTransaction
    {
        return new AppointmentTransaction();
    }

    public function transactionWithRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->appointmentTransaction()->with('user', 'service_provider', 'appointment');
    }

    public function transactionById($id){
        return $this->transactionWithRelations()->findOrFail($id);
    }

    public function transactionByAppointmentId($id){
        return $this->transactionWithRelations()->where('appointment_id', $id)->first();
    }

}
