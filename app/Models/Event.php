<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'status', 'organizer_id', 'slug', 'thumbnail'];

    protected static function booted()
    {
        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = \Str::slug($event->title . '-' . substr(uniqid(),0,6));
            }
        });
    }

    public function subevents()
    {
        return $this->hasMany(SubEvent::class);
    }

    public function venue()
    {
        return $this->hasOne(Venue::class);
    }

    public function support()
    {
        return $this->hasOne(Support::class);
    }

    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, SubEvent::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function volunteers()
    {
        return $this->hasMany(Volunteer::class);
    }
}
