<!doctype html>
<html>
<head><meta charset="utf-8"><title>Tickets</title></head>
<body>
    <h1>Step 3: Tickets</h1>
    <form method="POST" action="{{ route('organizers.events.wizard.post', ['step' => 'tickets']) }}">
        @csrf
        <label>Map to Sub-Event</label>
        <select name="subevent_index">
            @php $ses = $data['subevents'] ?? [];
            $subs = $ses['subevents'] ?? $ses ?? [];
            @endphp
            @foreach($subs as $i => $s)
                <option value="{{ $i }}">{{ data_get($s,'title','Sub-event '.$i) }}</option>
            @endforeach
        </select>

        <label>Ticket name</label><input name="tickets[0][name]" />
        <label>Price</label><input name="tickets[0][price]" />
        <button type="submit">Next</button>
    </form>
</body>
</html>