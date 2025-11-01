<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

/**
 * Autoriza que cada usuario escuche su propio canal privado: App.Models.User.{id}
 * Solo el propietario (id coincide) puede suscribirse.
 */
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
