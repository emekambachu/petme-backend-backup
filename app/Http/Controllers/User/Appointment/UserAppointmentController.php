<?php

namespace App\Http\Controllers\User\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Appointment\UserStoreAppointmentRequest;
use App\Services\Appointment\AppointmentService;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserAppointmentController extends Controller
{
    protected $appointment;
    public function __construct(AppointmentService $appointment){
        $this->appointment = $appointment;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        try {
            $appointments = $this->appointment->appointmentsByUserId(Auth::user()->id)
                ->orderBy('created_at', 'desc')->paginate(12);
            return response()->json([
                'success' => true,
                'total' => $appointments->total(),
                'appointments' => $appointments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store(UserStoreAppointmentRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->createAppointmentForUser($request, Auth::user()->id);
            return response()->json([
                'success' => $data['success'],
                'appointment' => $data['appointment'],
                'message' => $data['message']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(UserStoreAppointmentRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->rescheduleAppointmentForUser($request, $id, Auth::user()->id);
            return response()->json([
                'success' => $data['success'],
                'appointment' => $data['appointment'],
                'message' => $data['message']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->deleteAppointmentForUser($id, Auth::user()->id);
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function addService($serviceId, $appointmentId): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->addServiceToAppointment($serviceId, $appointmentId);
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function removeService($serviceId, $appointmentId): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $this->appointment->removeServiceFromAppointment($serviceId, $appointmentId);
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


}
