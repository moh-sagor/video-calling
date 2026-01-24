<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Presence channel for online users
Broadcast::channel('online', function ($user) {
    if (auth()->check()) {
        return $user->toArray();
    }
});

// Private channel for user notifications
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel for group chat
Broadcast::channel('group.{id}', function ($user, $id) {
    // Check if user is member of the group
    return \App\Models\Group::find($id)->members->contains('id', $user->id);
});
