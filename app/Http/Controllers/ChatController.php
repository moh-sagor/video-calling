<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use App\Events\GroupCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'receiver_id' => 'nullable|exists:users,id',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        if (!$request->receiver_id && !$request->group_id) {
            return response()->json(['error' => 'Receiver or Group required'], 400);
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'group_id' => $request->group_id,
            'body' => $request->body,
        ]);

        // Load relationships for broadcasting
        $message->load('sender');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'required|array',
            'members.*' => 'exists:users,id',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        // Add creator
        $group->members()->attach(Auth::id());
        
        // Add other members
        $group->members()->attach($request->members);

        $loadGroup = Group::with('members')->find($group->id);

        broadcast(new GroupCreated($loadGroup))->toOthers();

        return response()->json($loadGroup);
    }

    public function fetchMessages(Request $request)
    {
        $query = Message::with('sender');

        if ($request->has('group_id')) {
            $query->where('group_id', $request->group_id);
        } elseif ($request->has('user_id')) {
            $query->where(function($q) use ($request) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $request->user_id);
            })->orWhere(function($q) use ($request) {
                $q->where('sender_id', $request->user_id)->where('receiver_id', Auth::id());
            });
        } else {
            return response()->json([]);
        }

        $messages = $query->oldest()->get();

        // Mark fetched messages as read
        if ($request->has('user_id')) {
            Message::where('sender_id', $request->user_id)
                ->where('receiver_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json($messages);
    }
    
    public function fetchGroups() {
        return response()->json(Auth::user()->groups()->get());
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id'
        ]);

        Message::where('sender_id', $request->sender_id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'success']);
    }

    public function fetchUnreadCounts()
    {
        $counts = Message::selectRaw('sender_id, count(*) as count')
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->groupBy('sender_id')
            ->get()
            ->pluck('count', 'sender_id');

        return response()->json($counts);
    }
}
