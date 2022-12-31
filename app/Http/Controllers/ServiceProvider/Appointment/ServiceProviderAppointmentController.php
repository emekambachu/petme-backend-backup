<?php

namespace App\Http\Controllers\ServiceProvider\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceProvider\Appointment\ServiceProviderAppointmentCollection;
use App\Services\Appointment\AppointmentService;
use App\Services\Base\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceProviderAppointmentController extends Controller
{
    protected AppointmentService $appointment;
    public function __construct(AppointmentService $appointment){
        $this->appointment = $appointment;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->appointmentsByServiceProviderId(Auth::user()->id)
                ->orderBy('created_at', 'desc')->paginate(12);
            return response()->json([
                'success' => true,
                'total' => $data->total(),
                'appointments' => new ServiceProviderAppointmentCollection($data)
            ]);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function accept($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->serviceProviderAcceptAppointment($id, Auth::user()->id);
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function reject($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->serviceProviderRejectAppointment($id, Auth::user()->id);
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

    public function completed($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->serviceProviderCompletedAppointment($id, Auth::user()->id);
            return response()->json($data);

        } catch (\Exception $e) {
            return BaseService::tryCatchException($e);
        }
    }

}
