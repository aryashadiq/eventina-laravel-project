<!doctype html>
<html>
<head><meta charset="utf-8"><title>Sub-events</title></head>
<body>
    <h1>Step 2: Sub-events</h1>
    <form method="POST" action="{{ route('organizers.events.wizard.post', ['step' => 'subevents']) }}">
        @csrf
        <label>Sub-event title</label><input name="subevents[0][title]" />
        <label>Start</label><input name="subevents[0][start_at]" />
        <label>End</label><input name="subevents[0][end_at]" />
        <button type="submit">Next</button>
    </form>
</body>
</html>