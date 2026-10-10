{{-- Bot protection for public forms; checked by the "spam" route middleware. --}}
<input type="hidden" name="_form_time" value="{{ Crypt::encryptString((string) time()) }}">
<div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;" aria-hidden="true">
    <label>Leave this field empty <input type="text" name="website" value="" tabindex="-1" autocomplete="off"></label>
</div>
@if(config('services.turnstile.key'))
<div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.key') }}"></div>
@once
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endonce
@endif
@error('spam')<p style="color:#dc2626;font-size:.875rem;">{{ $message }}</p>@enderror
