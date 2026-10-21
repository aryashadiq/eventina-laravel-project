<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = ['event_id', 'contact_name', 'contact_email', 'contact_phone'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
