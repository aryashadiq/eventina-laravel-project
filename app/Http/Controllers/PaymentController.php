<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessPaymentJob;
use App\Models\PaymentNonce;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function buy(Ticket $ticket)
    {
        // load relations
        $ticket->loadMissing(['subevent.event']);
        $subevent = $ticket->subevent;
        $event = $subevent ? $subevent->event : null;

        return view('tickets.buy', compact('ticket', 'subevent', 'event'));
    }

    public function createQris(Request $request)
    {
        $invoiceId = $request->input('invoice_id') ?: $request->input('invoice');
        $invoice = null;
        if ($invoiceId) {
            $invoice = \App\Models\Invoice::find($invoiceId);
        }

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        if ((int) $invoice->user_id !== (int) Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Build Pakasir payment URL
        $slug = config('services.pakasir.slug');
        $amount = (int) $invoice->amount; // Ensure integer, no decimals
        $orderId = $invoice->id;
        $redirectUrl = config('services.pakasir.redirect_url') . '/payment/callback?invoice_id=' . $invoice->id;

        $paymentUrl = config('services.pakasir.gateway_url') . '/' . $slug . '/' . $amount
                    . '?order_id=' . $orderId 
                    . '&redirect=' . urlencode($redirectUrl);

        return response()->json([
            'ok' => true,
            'payment_url' => $paymentUrl,
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount,
            'order_id' => $orderId,
        ]);
    }

    public function pakasirWebhook(Request $request)
    {
        // Handle both GET redirect and POST webhook from Pakasir
        $payload = $request->all();
        
        // Log webhook for debugging
        \App\Models\PaymentLog::create([
            'provider' => 'pakasir',
            'payload' => $payload,
            'signature' => null,
        ]);

        // Get invoice ID from request (either from redirect or webhook)
        $invoiceId = $payload['invoice_id'] ?? $payload['order_id'] ?? null;
        if (!$invoiceId) {
            return response('missing invoice_id', 400);
        }

        $invoice = \App\Models\Invoice::find($invoiceId);
        if (!$invoice) {
            return response('invoice not found', 404);
        }

        // Never trust amount/status from the request itself (anyone can call this endpoint);
        // always confirm the transaction with the Pakasir API.
        $apiResponse = $this->checkPaymentStatusWithPakasirAPI($invoiceId, (int)$invoice->amount);

        if (!$apiResponse) {
            if ($request->isMethod('GET')) {
                return redirect()->route('dashboard')->with('error', 'Could not verify payment status');
            }
            return response('could not verify payment status', 400);
        }

        $amount = $apiResponse['amount'] ?? null;
        $status = $apiResponse['status'] ?? null;
        $paymentMethod = $apiResponse['payment_method'] ?? null;

        if (!$amount || !$status) {
            return response('missing required fields from API', 400);
        }

        // Verify amount matches
        if ((int)$invoice->amount !== (int)$amount) {
            return response('amount mismatch', 400);
        }

        // Check nonce to prevent replay attacks
        $nonce = $invoiceId . '_' . $status . '_' . $amount;
        if (PaymentNonce::where('nonce', $nonce)->exists()) {
            // Already processed, redirect to dashboard
            if ($request->isMethod('GET')) {
                return redirect()->route('dashboard')->with('status', 'Payment already processed');
            }
            return response('ok');
        }
        PaymentNonce::create(['nonce' => $nonce, 'source' => 'pakasir', 'used_at' => now()]);

        // Update payment status if completed
        if ($status === 'completed') {
            $payment = \App\Models\Payment::where('invoice_id', $invoiceId)->first();
            if ($payment) {
                $payment->update([
                    'paid' => true,
                    'verified' => true,
                    'method' => $paymentMethod ?? 'qris',
                    'transaction_id' => $invoiceId,
                ]);
            }

            // Dispatch job to handle post-payment logic
            $dispatchPayload = [
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'status' => $status,
                'payment_method' => $paymentMethod,
                'payment_id' => $payment ? $payment->id : null,
            ];
            ProcessPaymentJob::dispatch($dispatchPayload);
        }

        // If GET request (user redirect), go to dashboard
        if ($request->isMethod('GET')) {
            $message = $status === 'completed' ? 'Payment processed successfully' : 'Payment status: ' . $status;
            return redirect()->route('dashboard')->with('status', $message);
        }

        // If POST request (webhook), just respond with ok
        return response('ok');
    }

    private function checkPaymentStatusWithPakasirAPI($orderId, $amount)
    {
        try {
            $slug = config('services.pakasir.slug');
            $apiKey = config('services.pakasir.api_key');
            $apiUrl = config('services.pakasir.api_url');

            $url = $apiUrl . '?project=' . $slug . '&amount=' . $amount . '&order_id=' . $orderId . '&api_key=' . $apiKey;

            \Log::info('Calling Pakasir API', ['url' => $url]);

            $response = \Illuminate\Support\Facades\Http::timeout(10)->get($url);
            
            \Log::info('Pakasir API response', ['status' => $response->status(), 'body' => $response->body()]);

            if ($response->successful()) {
                $data = $response->json()['transaction'] ?? null;
                \Log::info('Pakasir transaction data', ['data' => $data]);
                return $data;
            } else {
                \Log::warning('Pakasir API failed', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            \Log::error('Pakasir API error: ' . $e->getMessage(), ['exception' => $e]);
        }

        return null;
    }
}
