<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ministry;

class MinistryController extends Controller
{
    public function index()
    {
        return response()->json(Ministry::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'coordinator' => 'required',
        ]);

        $ministry = Ministry::create($request->all());
        return response()->json($ministry, 201);
    }

    public function update(Request $request, $id)
    {
        $ministry = Ministry::findOrFail($id);
        $ministry->update($request->all());
        return response()->json($ministry);
    }

    public function destroy($id)
    {
        Ministry::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}