<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json(Notification::orderBy('created_at', 'desc')->take(20)->get());
    }

    public function markAsRead($id)
    {
        Notification::where('id', $id)->update(['is_read' => true]);
        return response()->json(['message' => 'Marked as read']);
    }
    
    public function markAllAsRead()
    {
        Notification::query()->update(['is_read' => true]);
        return response()->json(['message' => 'All read']);
    }
}