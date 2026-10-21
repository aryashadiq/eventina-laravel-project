<!doctype html>
<html>
<head><meta charset="utf-8"><title>General Info</title></head>
<body>
    <h1>Step 1: General Info</h1>
    <form method="POST" action="{{ route('organizers.events.wizard.post', ['step' => 'general']) }}">
        @csrf
        <label>Title</label><input name="title" value="{{ data_get($data,'general.title') }}" />
        <label>Description</label><textarea name="description">{{ data_get($data,'general.description') }}</textarea>
        <button type="submit">Next</button>
    </form>
</body>
</html>