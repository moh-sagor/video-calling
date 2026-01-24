<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Call;
use App\Models\User;
use App\Events\StartVideoCall;
use App\Events\AcceptVideoCall;
use App\Events\RejectVideoCall;
use App\Events\IceCandidate;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
    public function callUser(Request $request)
    {
        $data = $request->validate([
            'user_to_call' => 'required|exists:users,id',
            'offer' => 'required'
        ]);

        $call = Call::create([
            'caller_id' => Auth::id(),
            'receiver_id' => $data['user_to_call'],
            'status' => 'pending'
        ]);

        broadcast(new StartVideoCall([
            'offer' => $data['offer'],
            'from' => Auth::id(),
            'to' => $data['user_to_call'],
            'call_id' => $call->id,
            'caller_name' => Auth::user()->name
        ]));

        return response()->json(['call_id' => $call->id]);
    }

    public function acceptCall(Request $request)
    {
        $data = $request->validate([
            'caller_id' => 'required|exists:users,id',
            'answer' => 'required',
            'call_id' => 'required|exists:calls,id'
        ]);

        $call = Call::findOrFail($data['call_id']);
        $call->update(['status' => 'accepted']);

        broadcast(new AcceptVideoCall([
            'answer' => $data['answer'],
            'from' => Auth::id(),
            'to' => $data['caller_id'],
            'call_id' => $call->id
        ]));

        return response()->json(['status' => 'success']);
    }

    public function rejectCall(Request $request)
    {
        $data = $request->validate([
            'caller_id' => 'required|exists:users,id',
            'call_id' => 'required|exists:calls,id'
        ]);

        $call = Call::findOrFail($data['call_id']);
        $call->update(['status' => 'rejected']);

        broadcast(new RejectVideoCall([
            'from' => Auth::id(),
            'to' => $data['caller_id'],
            'call_id' => $call->id
        ]));

        return response()->json(['status' => 'success']);
    }

    public function iceCandidate(Request $request)
    {
        $data = $request->validate([
            'to' => 'required|exists:users,id',
            'candidate' => 'required',
            'call_id' => 'required' // Validation optional strictly speaking
        ]);

        broadcast(new IceCandidate([
            'candidate' => $data['candidate'],
            'from' => Auth::id(),
            'to' => $data['to'],
            'call_id' => $data['call_id']
        ]));

        return response()->json(['status' => 'success']);
    }
}
