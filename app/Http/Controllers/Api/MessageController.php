<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Get list of users to chat with (excluding self)
    public function users()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return response()->json($users);
    }

    // Get conversation with a specific user
    public function index($userId)
    {
        $myId = Auth::id();

        // Mark messages as read
        Message::where('sender_id', $userId)
               ->where('receiver_id', $myId)
               ->update(['is_read' => true]);

        $messages = Message::where(function($q) use ($myId, $userId) {
                        $q->where('sender_id', $myId)->where('receiver_id', $userId);
                    })
                    ->orWhere(function($q) use ($myId, $userId) {
                        $q->where('sender_id', $userId)->where('receiver_id', $myId);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();

        return response()->json($messages);
    }

    // Send a message
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        return response()->json($message, 201);
    }
    
    // Get unread count
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())->where('is_read', false)->count();
        return response()->json(['count' => $count]);
    }
}