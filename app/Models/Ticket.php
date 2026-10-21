<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['sub_event_id', 'code', 'currency', 'price', 'tax'];

    protected $table = 'tickets';

    public static function boot()
    {
        parent::boot();

        static::created(function (Ticket $ticket) {
            // Generate a unique code if empty
            try {
                if (empty($ticket->code)) {
                    $random = strtoupper(bin2hex(random_bytes(3)));
                    $ticket->code = 'TCKT-' . $ticket->id . '-' . $random;
                    $ticket->save();
                }
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }

    public function subevent()
    {
        return $this->belongsTo(SubEvent::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
