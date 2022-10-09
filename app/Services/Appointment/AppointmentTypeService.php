<?php

namespace App\Services\Appointment;

use App\Models\Appointment\AppointmentType;

/**
 * Class AppointmentTypeService.
 */
class AppointmentTypeService
{
    public function appointmentType(): AppointmentType
    {
        return new AppointmentType();
    }

    public function appointmentTypeById($id)
    {
        return $this->appointmentType()->findOrFail($id);
    }
}
