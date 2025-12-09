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

// Real-time notifications channel for transactions
Broadcast::channel('transaction-channel.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Real-time notifications for all admins
Broadcast::channel('admin-transaction-channel', function ($user) {
    return $user->hasRole('admin');
});

// Real-time notifications for specific user types
Broadcast::channel('transaction-notifications.{role}', function ($user, $role) {
    return $user->hasRole($role);
});