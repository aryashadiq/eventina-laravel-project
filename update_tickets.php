<?php

require __DIR__ . '/vendor/autoload.php';

// Load and boot Laravel
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the first SubEvent
$subevent = \App\Models\SubEvent::first();

if ($subevent) {
    $updated = \App\Models\Ticket::whereNull('subevent_id')->update(['subevent_id' => $subevent->id]);
    echo "✓ Updated $updated tickets with subevent_id = {$subevent->id}\n";
    echo "✓ Event: {$subevent->event->title}\n";
} else {
    echo "✗ No SubEvent found in database\n";
}
