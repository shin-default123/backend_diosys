<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule; 
use App\Models\Notification;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'nullable|email', 
        ]);

        $booking = Booking::create($request->all());

        Notification::create([
        'type' => 'Booking',
        'title' => 'New Booking Request',
        'message' => "A new {$request->type} request for {$request->date} has been received.",
        'is_read' => false
    ]);

        return response()->json(['message' => 'Booking request submitted successfully!', 'data' => $booking], 201);
    }

    // ADMIN: View all bookings (Pending, Approved, Rejected)
    public function index()
    {
        return response()->json(Booking::orderBy('created_at', 'desc')->get());
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        if ($request->has('status')) {
            $booking->status = $request->status;

            if ($request->status === 'Approved') {
                Schedule::create([
                    'type' => $booking->type,
                    'date' => $request->has('date') ? $request->date : $booking->date,
                    'time' => $booking->time,
                    'place' => $request->has('venue') ? $request->venue : $booking->venue,
                    'priest' => 'Assigned Priest' 
                ]);
            }
        }

        if ($request->has('date')) {
            $booking->date = $request->date;
        }
        if ($request->has('time')) {
            $booking->time = $request->time;
        }
        if ($request->has('venue')) {
            $booking->venue = $request->venue;
        }

        $booking->save();

        return response()->json(['message' => 'Booking updated successfully']);
    }
}