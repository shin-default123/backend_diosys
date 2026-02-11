<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parish;

class ParishController extends Controller
{
    public function index()
    {
        return response()->json(Parish::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'priest' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $parish = Parish::create($request->all());

        return response()->json(['message' => 'Parish created', 'data' => $parish], 201);
    }

    public function destroy($id)
    {
        $parish = Parish::find($id);
        if ($parish) {
            $parish->delete();
            return response()->json(['message' => 'Parish removed']);
        }
        return response()->json(['message' => 'Parish not found'], 404);
    }
}