<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Appointment::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'healthcare_professional_id' => 'required|exists:healthcare_professionals,id',
            'appointment_start_time' => 'required|date|date_format:Y-m-d H:i:s',
            'appointment_end_time' => 'required|date|date_format:Y-m-d H:i:s|after:appointment_start_time',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        if ($this->passes($request->healthcare_professional_id, $request->appointment_start_time, $request->appointment_end_time)) {
            $appointment = Appointment::create([
                'user_id' => auth('api')->id(),
                'healthcare_professional_id' => $request->healthcare_professional_id,
                'appointment_start_time' => $request->appointment_start_time,
                'appointment_end_time' => $request->appointment_end_time,
                'status' => 'booked',
            ]);

            return response()->json($appointment, 201);
        } else {
            return response()->json(['message' => 'Please select diffrent slot, Doc already have appoinment for this slot'], 201);
        }
    }

    public function passes($healthcareProfessionalId, $appointmentStartTime, $appointmentEndTime)
    {
        $appointments = Appointment::where('healthcare_professional_id', $healthcareProfessionalId)
            ->where(function ($query) use ($appointmentStartTime, $appointmentEndTime) {
                $query->where(function ($q) use ($appointmentStartTime, $appointmentEndTime) {
                    $q->where('appointment_start_time', '<', $appointmentEndTime)
                        ->where('appointment_end_time', '>', $appointmentStartTime);
                })
                    ->orWhere(function ($q) use ($appointmentStartTime, $appointmentEndTime) {
                        $q->where('appointment_start_time', '>=', $appointmentStartTime)
                            ->where('appointment_start_time', '<', $appointmentEndTime);
                    })
                    ->orWhere(function ($q) use ($appointmentStartTime, $appointmentEndTime) {
                        $q->where('appointment_end_time', '<=', $appointmentEndTime)
                            ->where('appointment_end_time', '>', $appointmentStartTime);
                    })
                    ->orWhere(function ($q) use ($appointmentStartTime, $appointmentEndTime) {
                        $q->where('appointment_start_time', '<=', $appointmentStartTime)
                            ->where('appointment_end_time', '>=', $appointmentEndTime);
                    });
            })
            ->get();
        return count($appointments) == 0 ? true : false;
    }


    public function list()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        $appointments = $user->appointments()->select('id', 'appointment_start_time', 'appointment_end_time', 'status')->get();
        return response()->json($appointments);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->all());
        return $appointment;
    }

    public function cancel($id)
    {

        // Retrieve the logged-in user
        $user = auth()->user();

        // Find appointment associated with the user
        $appointment = $user->appointments()->find($id);
        if ($appointment) {

            // Check if appointment can be canceled (before 24 hours)
            $twentyFourHoursFromNow = Carbon::now()->addHours(24);
            if ($appointment->appointment_start_time->gt($twentyFourHoursFromNow)) {
                if ($appointment->status == 'booked') {
                    $appointment->status = 'cancelled';
                    $appointment->save();
                    return response()->json(['message' => 'Appointment cancelled successfully'], 200);
                } else {

                    return response()->json(['message' => "Can't cancelled this appoinment"], 200);
                }
            }

            return response()->json(['message' => 'Cannot cancel appointment within 24 hours of start time'], 400);
        }
        return response()->json(['message' => 'Appoinment is not found'], 400);
    }

    public function complete($id)
    {

        // Retrieve the logged-in user
        $user = auth()->user();

        // Find appointment associated with the user
        $appointment = $user->appointments()->find($id);
        if ($appointment) {
            
            $currentTime = Carbon::now();
            if ($appointment->appointment_end_time->lt($currentTime)) {
                if ($appointment->status == 'booked') {
                    $appointment->status = 'complete';
                    $appointment->save();
                    return response()->json(['message' => 'Appointment completed successfully'], 200);
                } else {

                    return response()->json(['message' => "Can't complete this appoinment"], 200);
                }
            }

            return response()->json(['message' => 'Cannot complete appoinment before endtime'], 400);
        }
        return response()->json(['message' => 'Appoinment is not found'], 400);
    }
}
