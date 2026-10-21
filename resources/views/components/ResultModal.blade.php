@props(['id' => 'result-modal', 'title' => '', 'description' => '', 'downloadLink' => '#'])

<label for="{{ $id }}" class="text-sm">View result</label>

{{-- Use the PopupModal component (Blade) to render modal markup. --}}
<x-PopupModal :id="$id" :title="$title" :description="$description" :download-link="$downloadLink" />
