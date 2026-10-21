<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable = ['provider','payload','signature'];
    protected $casts = ['payload' => 'array'];
}
