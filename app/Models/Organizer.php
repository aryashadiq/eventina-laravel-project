<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Organizer extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    protected $guard = 'organizer';

    protected $fillable = ['name', 'email', 'password', 'phone'];
}
