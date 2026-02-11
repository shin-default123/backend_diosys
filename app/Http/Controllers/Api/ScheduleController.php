<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function index()
    {
        return response()->json(Schedule::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string', 
            'date' => 'required|string',
            'time' => 'required',
            'place' => 'required|string',
        ]);
        
        $schedule = Schedule::create($request->all());
        return response()->json($schedule, 201);
    }

    public function destroy($id)
    {
        Schedule::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->all());
        return response()->json(['message' => 'Schedule updated', 'data' => $schedule]);
    }
}