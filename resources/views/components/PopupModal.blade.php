@props(['id' => 'popup-modal', 'title' => '', 'description' => '', 'downloadLink' => null])

{{-- Simple checkbox-based modal structure (works with DaisyUI classes) --}}
<input type="checkbox" id="{{ $id }}" class="modal-toggle" />
<div class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-bold">{{ $title }}</h3>
        <p class="py-4">{{ $description }}</p>

        @if($downloadLink && $downloadLink !== '#')
            <a href="{{ $downloadLink }}" class="btn">Download</a>
        @endif
    </div>
    <label class="modal-backdrop" for="{{ $id }}">Close</label>
</div>
