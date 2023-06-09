<?php


use BeyondCode\LaravelWebSockets\WebSockets\Channels\Channel;
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

Broadcast::channel('live.count.{channel}', function ($user, $channel) {
    return (int) $user->id === (int) Channel::where('slug', $channel)->first()->owner_id;
});
