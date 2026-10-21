<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentNonce extends Model
{
    protected $fillable = ['nonce','source','used_at'];
    public $timestamps = true;
}
