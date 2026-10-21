@extends('layouts.app')

@section('title', 'Invoice #'.$invoice->id)

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 md:flex md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('img/logo.svg') }}" alt="Eventina" class="w-28 h-auto">
                    <div>
                        <div class="text-sm text-gray-500">Invoice</div>
                        <div class="text-lg font-semibold">#{{ $invoice->id }}</div>
                        <div class="text-sm text-gray-400">{{ $invoice->created_at->format('Y-m-d') }}</div>
                    </div>
                </div>

                <div class="mt-4 md:mt-0 text-right">
                    <div class="text-sm text-gray-500">Payment status</div>
                    <div class="text-base font-medium">{{ optional($invoice->payment)->paid ? 'paid' : 'pending' }}</div>
                </div>
            </div>

            <div class="p-6 border-t">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium">Bill to</h3>
                        <div class="text-sm text-gray-700">{{ $invoice->user->name ?? 'Customer' }}</div>
                        <div class="text-sm text-gray-500">{{ $invoice->user->email ?? '' }}</div>

                        <div class="mt-6">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-gray-500">
                                        <th>Item</th>
                                        <th class="text-right">Qty</th>
                                        <th class="text-right">Unit</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->lines ?? [['desc'=>'Ticket','qty'=>1,'unit'=>number_format($invoice->amount,2)]] as $line)
                                        <tr class="border-t">
                                            <td class="py-3">{{ $line['desc'] ?? 'Ticket' }}</td>
                                            <td class="py-3 text-right">{{ $line['qty'] ?? 1 }}</td>
                                            <td class="py-3 text-right">{{ $line['unit'] ?? number_format($invoice->amount,2) }}</td>
                                            <td class="py-3 text-right">{{ number_format(($line['qty'] ?? 1) * floatval(str_replace(',','',$line['unit'] ?? $invoice->amount)),2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="flex justify-between"><span class="text-sm text-gray-500">Subtotal</span><span>{{ number_format($invoice->amount,2) }}</span></div>
                            <div class="flex justify-between mt-3 text-lg font-semibold"><span>Total</span><span>{{ number_format($invoice->amount,2) }}</span></div>

                            <div class="mt-6">
                                <button id="generate-qris" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Generate QRIS</button>
                                <div id="qris-result" class="mt-4 text-sm text-gray-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('generate-qris')?.addEventListener('click', function () {
            const btn = this;
            btn.disabled = true;
            btn.textContent = 'Redirecting to payment...';

            fetch('{{ route('qris.create') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ invoice_id: {{ $invoice->id }} })
            }).then(r => r.json())
            .then(data => {
                if (data.payment_url) {
                    // Redirect to Pakasir payment gateway
                    window.location.href = data.payment_url;
                } else {
                    alert('Payment URL not generated. Please try again.');
                    btn.disabled = false;
                    btn.textContent = 'Generate QRIS';
                }
            }).catch(err => {
                console.error(err);
                alert('Error generating payment. Please try again.');
                btn.disabled = false;
                btn.textContent = 'Generate QRIS';
            });
        });
    </script>

    
@endsection
