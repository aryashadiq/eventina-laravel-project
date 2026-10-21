<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class UserInvoiceController extends Controller
{
    public function view($invoice)
    {
        $inv = Invoice::findOrFail($invoice);
        
        // Ensure user can only view their own invoice
        if (Auth::user()->id !== $inv->user_id) {
            abort(403, 'Unauthorized');
        }
        
        return view('user.invoice', ['invoice' => $inv]);
    }

    public function download($invoice)
    {
        $inv = Invoice::findOrFail($invoice);
        
        // Ensure user can only download their own invoice
        if (Auth::user()->id !== $inv->user_id) {
            abort(403, 'Unauthorized');
        }
        
        $html = view('user.invoice', ['invoice' => $inv])->render();

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
}
