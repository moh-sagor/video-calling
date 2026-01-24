<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function create()
    {
        $code = Str::random(10); // Simple random string, can be improved
        
        // Ensure uniqueness
        while(Meeting::where('code', $code)->exists()){
            $code = Str::random(10);
        }

        Meeting::create([
            'code' => $code,
            'host_id' => Auth::id()
        ]);

        return redirect()->route('meet.join', ['code' => $code]);
    }

    public function join($code)
    {
        $meeting = Meeting::where('code', $code)->firstOrFail();
        
        return view('meeting', [
            'meeting' => $meeting,
            'user' => Auth::user()
        ]);
    }
}
