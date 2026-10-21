@extends('layouts.app')

@section('title', 'Invoice #'.$invoice->id)

@section('content')
<style>
    .invoice-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 16px;
    }
    
    .receipt-wrapper {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        overflow: hidden;
    }
    
    .receipt-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 32px 24px;
        text-align: center;
    }
    
    .receipt-header img {
        height: 40px;
        margin-bottom: 16px;
    }
    
    .receipt-company-name {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }
    
    .receipt-subtitle {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }
    
    .receipt-body {
        padding: 32px 24px;
    }
    
    .receipt-section {
        margin-bottom: 32px;
    }
    
    .receipt-section-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        margin: 0 0 12px 0;
    }
    
    .receipt-field {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
        color: #1f2937;
    }
    
    .receipt-field-label {
        color: #6b7280;
    }
    
    .receipt-field-value {
        font-weight: 500;
    }
    
    .receipt-divider {
        border: none;
        border-top: 1px solid #e5e7eb;
        margin: 24px 0;
    }
    
    .event-details {
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 24px;
    }
    
    .event-details-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        margin: 0 0 12px 0;
    }
    
    .event-name {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 8px 0;
    }
    
    .subevent-name {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }
    
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }
    
    .items-table thead {
        border-bottom: 2px solid #e5e7eb;
    }
    
    .items-table th {
        padding: 12px 0;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }
    
    .items-table td {
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .receipt-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px;
        margin-bottom: 24px;
    }
    
    .receipt-total-label {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .receipt-total-amount {
        font-size: 28px;
        font-weight: 700;
    }
    
    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-badge.paid {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .receipt-actions {
        display: flex;
        gap: 12px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn-action {
        flex: 1;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-download {
        background: #3b82f6;
        color: white;
    }
    
    .btn-download:hover {
        background: #2563eb;
    }
    
    .btn-back {
        background: #e5e7eb;
        color: #374151;
    }
    
    .btn-back:hover {
        background: #d1d5db;
    }
    
    .receipt-footer {
        background: #f9fafb;
        padding: 24px;
        text-align: center;
        border-top: 1px solid #e5e7eb;
    }
    
    .receipt-footer-text {
        font-size: 12px;
        color: #6b7280;
        margin: 0;
    }
    
    .payment-info {
        background: #f3f4f6;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }
    
    .payment-info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .payment-info-row:last-child {
        margin-bottom: 0;
    }
    
    .payment-info-label {
        color: #6b7280;
    }
    
    .payment-info-value {
        font-weight: 500;
        color: #1f2937;
    }
    
    @media (max-width: 640px) {
        .receipt-body {
            padding: 20px 16px;
        }
        
        .receipt-total {
            flex-direction: column;
            gap: 12px;
        }
        
        .receipt-total-amount {
            text-align: right;
            width: 100%;
        }
    }
    
    @media print {
        .receipt-actions,
        .receipt-header {
            display: none;
        }
        
        body {
            background: white;
        }
        
        .receipt-wrapper {
            box-shadow: none;
            border: none;
        }
    }
</style>

<div class="invoice-container" style="margin-top: 32px; margin-bottom: 32px;">
    <div class="receipt-wrapper">
        <!-- Header -->
        <div class="receipt-header">
            <img src="{{ asset('img/logo.svg') }}" alt="Eventina">
            <h1 class="receipt-company-name">EVENTINA</h1>
            <p class="receipt-subtitle">Invoice Receipt</p>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <!-- Invoice Info -->
            <div class="receipt-section">
                <h3 class="receipt-section-title">Invoice Information</h3>
                <div class="receipt-field">
                    <span class="receipt-field-label">Invoice Number</span>
                    <span class="receipt-field-value">#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="receipt-field">
                    <span class="receipt-field-label">Date</span>
                    <span class="receipt-field-value">{{ $invoice->created_at->format('M d, Y') }}</span>
                </div>
                <div class="receipt-field">
                    <span class="receipt-field-label">Time</span>
                    <span class="receipt-field-value">{{ $invoice->created_at->format('H:i') }}</span>
                </div>
                <div class="receipt-field">
                    <span class="receipt-field-label">Status</span>
                    <span class="receipt-field-value">
                        @if($invoice->payment && $invoice->payment->paid)
                            <span class="status-badge paid">✓ Paid</span>
                        @else
                            <span class="status-badge pending">⏳ Pending Payment</span>
                        @endif
                    </span>
                </div>
            </div>

            <div class="receipt-divider"></div>

            <!-- Customer Info -->
            <div class="receipt-section">
                <h3 class="receipt-section-title">Customer</h3>
                <div class="receipt-field">
                    <span class="receipt-field-label">Name</span>
                    <span class="receipt-field-value">{{ $invoice->user->name ?? 'Guest' }}</span>
                </div>
                <div class="receipt-field">
                    <span class="receipt-field-label">Email</span>
                    <span class="receipt-field-value">{{ $invoice->user->email ?? '-' }}</span>
                </div>
            </div>

            <div class="receipt-divider"></div>

            <!-- Event Details -->
            <div class="receipt-section">
                <h3 class="receipt-section-title">Event Details</h3>
                <div class="event-details">
                    <h4 class="event-name">
                        {{ optional(optional(optional($invoice->ticket)->subevent)->event)->title ?? 'Event' }}
                    </h4>
                    <p class="subevent-name">
                        {{ optional(optional($invoice->ticket)->subevent)->title ?? 'Sub Event' }}
                    </p>
                </div>
            </div>

            <div class="receipt-divider"></div>

            <!-- Items -->
            <div class="receipt-section">
                <h3 class="receipt-section-title">Items</h3>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th style="text-align: right;">Qty</th>
                            <th style="text-align: right;">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>{{ optional(optional(optional($invoice->ticket)->subevent)->event)->title ?? 'Event Ticket' }} - {{ $invoice->ticket->code }}</strong><br>
                                <small style="color: #6b7280;">{{ optional(optional($invoice->ticket)->subevent)->title ?? 'Event Ticket' }}</small>
                            </td>
                            <td style="text-align: right;">1</td>
                            <td style="text-align: right; font-weight: 600;">
                                {{ number_format($invoice->amount, 0, ',', '.') }} {{ $invoice->currency }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="receipt-total">
                <span class="receipt-total-label">Total Amount</span>
                <span class="receipt-total-amount">{{ number_format($invoice->amount, 0, ',', '.') }} {{ $invoice->currency }}</span>
            </div>

            <!-- Payment Info -->
            @if($invoice->payment)
                <div class="payment-info">
                    <div class="payment-info-row">
                        <span class="payment-info-label">Payment Method</span>
                        <span class="payment-info-value">{{ ucfirst($invoice->payment->method ?? 'Not specified') }}</span>
                    </div>
                    @if($invoice->payment->transaction_id)
                        <div class="payment-info-row">
                            <span class="payment-info-label">Transaction ID</span>
                            <span class="payment-info-value">{{ $invoice->payment->transaction_id }}</span>
                        </div>
                    @endif
                    @if($invoice->payment->paid)
                        <div class="payment-info-row">
                            <span class="payment-info-label">Paid At</span>
                            <span class="payment-info-value">{{ $invoice->payment->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Actions -->
            <div class="receipt-actions">
                <a href="{{ route('user.invoice.download', $invoice->id) }}" class="btn-action btn-download">
                    📥 Download PDF
                </a>
                <a href="{{ route('dashboard') }}" class="btn-action btn-back">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p class="receipt-footer-text">
                Thank you for your purchase! For support, contact us at {{ config('mail.from.address', 'support@eventina.com') }}
            </p>
        </div>
    </div>
</div>
@endsection
