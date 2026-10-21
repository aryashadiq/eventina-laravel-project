<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function createFromTicket(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        $userId = Auth::id();

        $amount = $ticket->price;
        $currency = $ticket->currency ?? 'IDR';

        // optional convert to requested currency
        $target = $request->input('currency');
        if ($target && $target !== $currency) {
            $converter = new \App\Services\CurrencyConverter();
            $amount = $converter->convert($amount, $currency, $target);
            $currency = $target;
        }

        $invoice = Invoice::create([
            'user_id' => $userId,
            'ticket_id' => $ticket->id,
            'amount' => $amount,
            'currency' => $currency,
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'paid' => false,
            'verified' => false,
            'method' => 'qris',
        ]);

        return redirect()->route('invoice.view', ['invoice' => $invoice->id]);
    }

    public function view($invoice)
    {
        $inv = $this->findOwnedInvoice($invoice);
        return view('invoice', ['invoice' => $inv]);
    }

    public function download($invoice)
    {
        $inv = $this->findOwnedInvoice($invoice);
        $html = view('invoice', ['invoice' => $inv])->render();

        // Use spipu/html2pdf if available
        try {
            $html2pdf = new \Spipu\Html2Pdf\Html2Pdf();
            $html2pdf->writeHTML($html);
            return response($html2pdf->output('', 'S'))->header('Content-Type', 'application/pdf');
        } catch (\Throwable $e) {
            // fallback to HTML
            return response($html);
        }
    }

    private function findOwnedInvoice($invoice): Invoice
    {
        $inv = Invoice::findOrFail($invoice);

        if ((int) $inv->user_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return $inv;
    }
}
