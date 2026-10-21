<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle()
    {
        // idempotency: if payment already marked paid, exit
        if (empty($this->payload['payment_id'])) return;

        $payment = Payment::find($this->payload['payment_id']);
        if (!$payment) return;
        if ($payment->paid && $payment->verified) return;

        $payment->paid = true;
        $payment->verified = true;
        if (!empty($this->payload['transaction_id'])) {
            $payment->transaction_id = $this->payload['transaction_id'];
        }
        $payment->save();
    }
}
