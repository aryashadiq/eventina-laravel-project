@props(['name' => null, 'value' => null])

<input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }} class="inline-block align-middle w-4 h-4" style="display:inline-block !important; width:auto !important; height:auto !important; vertical-align:middle;" />