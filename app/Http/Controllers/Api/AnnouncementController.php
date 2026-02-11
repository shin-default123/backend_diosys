<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        return response()->json(Announcement::where('is_active', true)->latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string']);
        $announcement = Announcement::create($request->all());
        return response()->json($announcement, 201);
    }

    public function destroy($id)
    {
        Announcement::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}