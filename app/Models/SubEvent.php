<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubEvent extends Model
{
    protected $fillable = ['event_id', 'start_at', 'end_at', 'title'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
