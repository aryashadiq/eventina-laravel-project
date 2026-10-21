<!doctype html>
<html>
<head><meta charset="utf-8"><title>Support</title></head>
<body>
    <h1>Step 4: Support</h1>
    <form method="POST" action="{{ route('organizers.events.wizard.post', ['step' => 'support']) }}">
        @csrf
        <label>Contact name</label><input name="contact_name" />
        <label>Contact email</label><input name="contact_email" />
        <button type="submit">Finish</button>
    </form>
</body>
</html>