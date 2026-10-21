{{--
    Stepper component: server-rendered version. For interactive multi-step behavior use Livewire.
    Props: steps (array of ['title'=>..]), step (current index 1-based), totalSteps, formHtml (HTML string for current form)
--}
@props(['steps' => [], 'step' => 1, 'totalSteps' => 1, 'formHtml' => ''])

<div class="max-w-full mx-auto mt-8">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <ul class="steps w-full mb-3">
            @foreach($steps as $index => $s)
                <li class="step dark:text-white {{ $step >= ($index+1) ? 'step-primary' : '' }}">{{ $s['title'] ?? $s }}</li>
            @endforeach
        </ul>

        <div class="flex justify-center mt-5">{!! $formHtml !!}</div>

        <div class="flex justify-between mt-10">
            <button class="btn btn-accent text-white py-2 px-4 rounded-lg mr-4" @if($step <= 1) disabled @endif>Previous</button>
            <button class="btn btn-primary hover:bg-blue-600 text-white py-2 px-4 rounded-lg" @if($step >= $totalSteps) disabled @endif>Next</button>
        </div>
    </div>
</div>
