<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Create a new message
        $message = Message::create([
            'user_name' => $request->input('user_name'),
            'message' => $request->input('message'),
        ]);

        // Broadcast the message
        broadcast(new MessageSent($message));

        // Return a response
        return response()->json(['status' => 'Message sent!']);
    }

    public function getMessages()
    {
        // Retrieve all messages from the database
        $messages = Message::all();

        // Return messages as JSON
        return response()->json($messages);
    }
}
