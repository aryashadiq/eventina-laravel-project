@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')

@if (session('status') || session('error'))
<div id="messageModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-content">
            <div class="modal-icon">
                @if(session('status'))
                    ✅
                @else
                    ❌
                @endif
            </div>
            <h2>
                @if(session('status'))
                    Success
                @else
                    Error
                @endif
            </h2>
            <p>
                @if(session('status'))
                    {{ session('status') }}
                @else
                    {{ session('error') }}
                @endif
            </p>
            <button onclick="closeModal()" class="modal-button">OK</button>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        const modal = document.getElementById('messageModal');
        modal.style.animation = 'slideDown 0.3s ease-out';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }
</script>
@endif

<style>
    /* Modal Popup Styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .modal-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 90%;
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }

    .modal-content {
        padding: 40px 30px;
        text-align: center;
    }

    .modal-icon {
        font-size: 60px;
        margin-bottom: 20px;
        display: block;
    }

    .modal-content h2 {
        margin: 0 0 15px 0;
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    .modal-content p {
        margin: 0 0 30px 0;
        font-size: 16px;
        color: #6b7280;
        line-height: 1.6;
    }

    .modal-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .modal-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
    }

    .modal-button:active {
        transform: translateY(0);
    }

    /* Dashboard Styles */
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
    }
    
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .dashboard-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card.paid {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .stat-card.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .stat-label {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
        margin-bottom: 8px;
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 700;
        margin: 0;
    }
    
    .content-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        padding: 24px;
    }
    
    .content-card h2 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }
    
    .tickets-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .tickets-table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .tickets-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }
    
    .tickets-table td {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        color: #1f2937;
    }
    
    .tickets-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .event-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .event-title {
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }
    
    .subevent-name {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    
    .ticket-code {
        background: #f3f4f6;
        color: #374151;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-family: 'Courier New', monospace;
        font-weight: 500;
    }
    
    .amount {
        font-weight: 600;
        color: #1f2937;
    }
    
    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge.paid {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge.pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .btn-view {
        display: inline-block;
        padding: 8px 16px;
        background: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        transition: background 0.2s ease;
    }
    
    .btn-view:hover {
        background: #2563eb;
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
    }
    
    .empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }
    
    .empty-text {
        color: #6b7280;
        margin-bottom: 16px;
        font-size: 16px;
    }
    
    .btn-browse {
        display: inline-block;
        padding: 10px 24px;
        background: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        transition: background 0.2s ease;
    }
    
    .btn-browse:hover {
        background: #2563eb;
        color: white;
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>My Dashboard</h1>
        <a href="{{ route('events.index') }}" class="btn-browse">Browse Events</a>
    </div>

    @if(session('status'))
        <div class="flash" style="margin-bottom: 24px;">{{ session('status') }}</div>
    @endif

    <!-- Stats Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Invoices</div>
            <p class="stat-number">{{ $invoices->count() }}</p>
        </div>
        <div class="stat-card paid">
            <div class="stat-label">Paid</div>
            <p class="stat-number">{{ $paid_count }}</p>
        </div>
        <div class="stat-card pending">
            <div class="stat-label">Pending</div>
            <p class="stat-number">{{ $pending_count }}</p>
        </div>
    </div>

    <!-- Tickets Section -->
    <div class="content-card">
        <h2>Your Tickets</h2>
        
        @if($invoices->count() > 0)
            <div style="overflow-x: auto;">
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Ticket Code</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>
                                    <div class="event-info">
                                        <p class="event-title">
                                            {{ optional(optional(optional($invoice->ticket)->subevent)->event)->title ?? 'Event' }}
                                        </p>
                                        <p class="subevent-name">
                                            {{ optional(optional($invoice->ticket)->subevent)->title ?? 'Sub Event' }}
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <span class="ticket-code">{{ $invoice->ticket->code }}</span>
                                </td>
                                <td>
                                    <span class="amount">{{ number_format($invoice->amount, 0, ',', '.') }} {{ $invoice->currency }}</span>
                                </td>
                                <td>
                                    @if($invoice->payment && $invoice->payment->paid)
                                        <span class="badge paid">✓ Paid</span>
                                    @else
                                        <span class="badge pending">⏳ Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('user.invoice.view', $invoice->id) }}" class="btn-view">View Invoice</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon">🎫</div>
                <p class="empty-text">You haven't purchased any tickets yet.</p>
                <a href="{{ route('events.index') }}" class="btn-browse">Browse Events</a>
            </div>
        @endif
    </div>
</div>
@endsection

