<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Venue;

class VenueController extends Controller
{
    public function index()
    {
        return response()->json(Venue::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $venue = Venue::create($request->all());
        return response()->json($venue, 201);
    }

    public function update(Request $request, $id)
    {
        $venue = Venue::findOrFail($id);
        $venue->update($request->all());
        return response()->json($venue);
    }

    public function destroy($id)
    {
        Venue::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}