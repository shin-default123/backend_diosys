<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
{
    public function index()
    {
        return response()->json(School::where('is_active', true)->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $school = School::create($request->all());

        return response()->json(['message' => 'School added successfully', 'data' => $school], 201);
    }

    public function destroy($id)
    {
        $school = School::find($id);
        if ($school) {
            $school->delete();
            return response()->json(['message' => 'School removed']);
        }
        return response()->json(['message' => 'School not found'], 404);
    }
}