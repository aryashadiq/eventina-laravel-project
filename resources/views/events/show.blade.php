@extends('layouts.app')

@section('title', $event->title)

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4">
        <div class="w-full max-w-4xl">
            {{-- Success Message Modal --}}
            @if(session('status'))
            <div id="messageModal" class="modal-overlay">
                <div class="modal-container">
                    <div class="modal-content">
                        <div class="modal-icon">✅</div>
                        <h2>Success</h2>
                        <p>{{ session('status') }}</p>
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
            </style>
            @endif

            {{-- Event Header --}}
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white">
                    <h1 class="text-4xl font-bold mb-2">{{ $event->title }}</h1>
                    <p class="text-lg opacity-90">{{ $event->description }}</p>
                </div>

                {{-- Organizer Info --}}
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold mb-2">Organizer</h3>
                    <p class="text-gray-600">{{ $event->organizer->name ?? 'Not specified' }}</p>
                </div>
            </div>

            {{-- Sessions/Sub Events --}}
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-6">Sessions</h2>

                @if($event->subevents->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500">No sessions available for this event.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($event->subevents as $subevent)
                            <div class="flex justify-between items-center p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-lg">{{ $subevent->title ?? 'Session' }}</h4>
                                    <p class="text-gray-600 text-sm">{{ Str::limit($subevent->description ?? '', 120) }}</p>
                                </div>
                                <div class="ml-4">
                                    @php $firstTicket = $subevent->tickets->first(); @endphp
                                    @if($firstTicket)
                                        <a href="{{ route('tickets.buy.show', ['ticket' => $firstTicket->id]) }}" 
                                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                            Buy
                                        </a>
                                    @else
                                        <button class="bg-gray-300 text-gray-500 px-4 py-2 rounded cursor-not-allowed" disabled>
                                            No tickets
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Back to Events --}}
            <div class="text-center">
                <a href="{{ route('events.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition">
                    Back to Events
                </a>
            </div>
        </div>
    </div>
@endsection
