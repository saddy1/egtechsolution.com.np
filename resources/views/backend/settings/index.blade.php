@extends('backend.layout.app')

@section('content')
@php
  $input = 'mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500';
  $label = 'block text-sm font-medium text-gray-700';
  $btn = 'inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700';
  $btn2 = 'inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50';
  $tabs = ['email' => ['fa-envelope', 'Email (SMTP)'], 'whatsapp' => ['fa-brands fa-whatsapp', 'WhatsApp'], 'security' => ['fa-fingerprint', 'Passkeys']];
@endphp
<section class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="mx-auto max-w-5xl space-y-6">
    <h5 class="text-2xl font-bold tracking-tight text-gray-900">Settings</h5>

    @if (session('success'))
      <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
      <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        <ul class="list-disc pl-5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    <nav class="flex gap-1 overflow-x-auto border-b border-gray-200">
      @foreach ($tabs as $key => [$icon, $name])
        <a href="{{ route('admin.settings', ['tab' => $key]) }}"
           class="flex items-center gap-2 whitespace-nowrap border-b-2 px-4 py-2.5 text-sm font-medium {{ $tab === $key ? 'border-indigo-600 text-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
          <i class="{{ str_starts_with($icon, 'fa-brands') ? $icon : 'fas ' . $icon }}"></i> {{ $name }}
        </a>
      @endforeach
    </nav>

    {{-- ================= EMAIL ================= --}}
    @if ($tab === 'email')
      <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('admin.settings.mail') }}" class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">
          @csrf
          <p class="text-sm text-gray-600">Used for order emails and password reset codes.</p>
          <div class="grid gap-4 sm:grid-cols-3">
            <div class="sm:col-span-2">
              <label for="mail_host" class="{{ $label }}">SMTP host</label>
              <input id="mail_host" name="mail_host" required value="{{ old('mail_host', $mail['host']) }}" placeholder="smtp.gmail.com" class="{{ $input }}">
            </div>
            <div>
              <label for="mail_port" class="{{ $label }}">Port</label>
              <input id="mail_port" name="mail_port" type="number" required value="{{ old('mail_port', $mail['port']) }}" class="{{ $input }}">
            </div>
          </div>
          <div>
            <label for="mail_encryption" class="{{ $label }}">Encryption</label>
            @php $enc = old('mail_encryption', $mail['encryption']); @endphp
            <select id="mail_encryption" name="mail_encryption" class="{{ $input }}">
              <option value="tls" @selected($enc === 'tls')>TLS (port 587)</option>
              <option value="ssl" @selected($enc === 'ssl')>SSL (port 465)</option>
              <option value="none" @selected($enc === 'none')>None</option>
            </select>
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="mail_username" class="{{ $label }}">Username</label>
              <input id="mail_username" name="mail_username" autocomplete="off" value="{{ old('mail_username', $mail['username']) }}" class="{{ $input }}">
            </div>
            <div>
              <label for="mail_password" class="{{ $label }}">Password / app password</label>
              <input id="mail_password" name="mail_password" type="password" autocomplete="new-password"
                     placeholder="{{ $mail['has_password'] ? '•••••••• (saved, leave empty to keep)' : '' }}" class="{{ $input }}">
            </div>
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="mail_from_address" class="{{ $label }}">From email</label>
              <input id="mail_from_address" name="mail_from_address" type="email" required value="{{ old('mail_from_address', $mail['from_address']) }}" class="{{ $input }}">
            </div>
            <div>
              <label for="mail_from_name" class="{{ $label }}">From name</label>
              <input id="mail_from_name" name="mail_from_name" required value="{{ old('mail_from_name', $mail['from_name']) }}" placeholder="EG Tech Solution" class="{{ $input }}">
            </div>
          </div>
          <button class="{{ $btn }}"><i class="fas fa-save"></i> Save email settings</button>
        </form>

        <div class="space-y-6">
          <form method="POST" action="{{ route('admin.settings.mail.test') }}" class="space-y-3 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            <h6 class="font-semibold text-gray-900">Send a test email</h6>
            <input name="to" type="email" required value="{{ old('to', auth('admin')->user()->email) }}" class="{{ $input }}">
            <button class="{{ $btn2 }}"><i class="fas fa-paper-plane"></i> Send test</button>
          </form>
          <div class="rounded-2xl border border-gray-200 bg-white p-6 text-sm text-gray-600 shadow-sm">
            <h6 class="font-semibold text-gray-900">Using Gmail?</h6>
            <ol class="mt-2 list-decimal space-y-1 pl-5">
              <li>Turn on 2-Step Verification in your Google account.</li>
              <li>Open <a href="https://myaccount.google.com/apppasswords" target="_blank" rel="noopener" class="text-indigo-600 underline">App passwords</a> and create one called "EG Tech website".</li>
              <li>Paste the 16-letter password here. Host <code>smtp.gmail.com</code>, port 587, TLS.</li>
            </ol>
            <p class="mt-2">Gmail allows about 500 emails a day.</p>
          </div>
        </div>
      </div>
    @endif

    {{-- ================= WHATSAPP ================= --}}
    @if ($tab === 'whatsapp')
      <div class="grid gap-6 lg:grid-cols-3">
        <form method="POST" action="{{ route('admin.settings.whatsapp') }}" class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">
          @csrf
          <label class="flex items-center gap-3 rounded-xl bg-gray-50 p-3">
            <input type="checkbox" name="whatsapp_enabled" value="1" class="h-5 w-5 rounded" @checked(old('whatsapp_enabled', $s['whatsapp_enabled'] ?? '0') === '1')>
            <span class="text-sm font-medium text-gray-900">Send WhatsApp messages for new orders</span>
          </label>

          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label for="whatsapp_phone_number_id" class="{{ $label }}">Phone number ID</label>
              <input id="whatsapp_phone_number_id" name="whatsapp_phone_number_id" inputmode="numeric" value="{{ old('whatsapp_phone_number_id', $s['whatsapp_phone_number_id'] ?? '') }}" placeholder="e.g. 106540352242922" class="{{ $input }}">
            </div>
            <div>
              <label for="whatsapp_api_version" class="{{ $label }}">Graph API version</label>
              <input id="whatsapp_api_version" name="whatsapp_api_version" required value="{{ old('whatsapp_api_version', $s['whatsapp_api_version'] ?? 'v23.0') }}" class="{{ $input }}">
            </div>
          </div>
          <div>
            <label for="whatsapp_token" class="{{ $label }}">Permanent access token</label>
            <input id="whatsapp_token" name="whatsapp_token" type="password" autocomplete="off"
                   placeholder="{{ !empty($s['whatsapp_token']) ? '•••••••• (saved, leave empty to keep)' : 'System user token from Meta Business settings' }}" class="{{ $input }}">
          </div>
          <div>
            <label for="whatsapp_shop_number" class="{{ $label }}">Shop alert number (your phone)</label>
            <input id="whatsapp_shop_number" name="whatsapp_shop_number" value="{{ old('whatsapp_shop_number', $s['whatsapp_shop_number'] ?? '') }}" placeholder="98XXXXXXXX" class="{{ $input }}">
            <p class="mt-1 text-xs text-gray-500">New-order alerts go here. Use a different number from the business number that sends them.</p>
          </div>
          <div class="grid gap-4 sm:grid-cols-3">
            <div>
              <label for="whatsapp_shop_template" class="{{ $label }}">Shop template</label>
              <input id="whatsapp_shop_template" name="whatsapp_shop_template" value="{{ old('whatsapp_shop_template', $s['whatsapp_shop_template'] ?? 'new_order_alert') }}" class="{{ $input }}">
            </div>
            <div>
              <label for="whatsapp_customer_template" class="{{ $label }}">Customer template</label>
              <input id="whatsapp_customer_template" name="whatsapp_customer_template" value="{{ old('whatsapp_customer_template', $s['whatsapp_customer_template'] ?? 'order_confirmation') }}" class="{{ $input }}">
            </div>
            <div>
              <label for="whatsapp_template_lang" class="{{ $label }}">Template language</label>
              <input id="whatsapp_template_lang" name="whatsapp_template_lang" required value="{{ old('whatsapp_template_lang', $s['whatsapp_template_lang'] ?? 'en') }}" class="{{ $input }}">
            </div>
          </div>
          <button class="{{ $btn }}"><i class="fas fa-save"></i> Save WhatsApp settings</button>
        </form>

        <form method="POST" action="{{ route('admin.settings.whatsapp.test') }}" class="h-fit space-y-3 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
          @csrf
          <h6 class="font-semibold text-gray-900">Send a test message</h6>
          <div>
            <label for="wa_to" class="{{ $label }}">To</label>
            <input id="wa_to" name="to" required value="{{ old('to', $s['whatsapp_shop_number'] ?? '') }}" placeholder="98XXXXXXXX" class="{{ $input }}">
          </div>
          <div>
            <label for="wa_tpl" class="{{ $label }}">Template</label>
            <select id="wa_tpl" name="template" class="{{ $input }}">
              <option value="hello_world">hello_world (Meta sample)</option>
              @foreach (array_filter([$s['whatsapp_shop_template'] ?? null, $s['whatsapp_customer_template'] ?? null]) as $t)
                <option value="{{ $t }}">{{ $t }}</option>
              @endforeach
            </select>
          </div>
          <button class="{{ $btn2 }}"><i class="fab fa-whatsapp"></i> Send test</button>
          <p class="text-xs text-gray-500">While your Meta app is in test mode, you can only send to numbers added in WhatsApp Manager → API setup.</p>
        </form>
      </div>

      <details class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm" {{ empty($s['whatsapp_phone_number_id']) ? 'open' : '' }}>
        <summary class="cursor-pointer font-semibold text-gray-900">Setup guide: connect your business number (about 30 minutes)</summary>
        <ol class="mt-4 list-decimal space-y-3 pl-5 text-sm text-gray-700">
          <li>Go to <a href="https://business.facebook.com" target="_blank" rel="noopener" class="text-indigo-600 underline">business.facebook.com</a> and create or open the <strong>EG Tech Solution</strong> business account.</li>
          <li>At <a href="https://developers.facebook.com/apps" target="_blank" rel="noopener" class="text-indigo-600 underline">developers.facebook.com/apps</a>, create an app (type <strong>Business</strong>) and add the <strong>WhatsApp</strong> product.</li>
          <li>In <strong>WhatsApp → API setup</strong>, click <strong>Add phone number</strong> and add your business number. Verify it with the SMS/call code.
            <br><span class="text-gray-500">Already using this number in the WhatsApp Business app? Choose the option to connect your existing WhatsApp Business app number (coexistence) so the app keeps working.</span></li>
          <li>Copy the <strong>Phone number ID</strong> shown there into the field above.</li>
          <li>In Business settings → <strong>Users → System users</strong>, add a system user (Admin), assign it your app and WhatsApp account, then <strong>Generate token</strong> with <code>whatsapp_business_messaging</code> and <code>whatsapp_business_management</code>, set to never expire. Paste it above.</li>
          <li>In WhatsApp Manager → <strong>Payment settings</strong>, add a card. Meta charges per message (utility messages are cheap; see Meta's pricing page).</li>
          <li>In WhatsApp Manager → <strong>Message templates</strong>, create these two templates. Category: <strong>Utility</strong>, language: <strong>English</strong>:
            <div class="mt-2 grid gap-3 md:grid-cols-2">
              <div class="rounded-lg bg-gray-50 p-3">
                <p class="font-mono text-xs font-semibold">new_order_alert</p>
                <p class="mt-1 text-xs">New order #@{{1}} from @{{2}} (@{{3}}). Total: Rs @{{4}}. Items: @{{5}}. Please confirm it in the admin panel.</p>
              </div>
              <div class="rounded-lg bg-gray-50 p-3">
                <p class="font-mono text-xs font-semibold">order_confirmation</p>
                <p class="mt-1 text-xs">Hi @{{1}}, thank you for your order #@{{2}} at EG Tech Solution. Total: Rs @{{3}}. Our team will call you shortly to confirm delivery.</p>
              </div>
            </div>
            <span class="text-gray-500">Approval usually takes minutes to a few hours.</span></li>
          <li>Save the settings, send a <strong>hello_world</strong> test, then test your own templates, and finally tick <strong>Send WhatsApp messages for new orders</strong>.</li>
        </ol>
      </details>
    @endif

    {{-- ================= PASSKEYS ================= --}}
    @if ($tab === 'security')
      <div class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <div>
          <h6 class="font-semibold text-gray-900">Passkeys</h6>
          <p class="mt-1 text-sm text-gray-600">Log in to admin with your fingerprint, Face ID or phone screen lock instead of typing a password. Your password still works as a backup.</p>
        </div>

        <div class="flex flex-col gap-2 sm:flex-row">
          <input id="passkeyName" maxlength="100" placeholder="Name this device, e.g. My iPhone" class="{{ $input }} sm:max-w-xs">
          <button type="button" id="addPasskey" class="{{ $btn }}"><i class="fas fa-fingerprint"></i> Add a passkey</button>
        </div>
        <p id="passkeyMsg" class="hidden text-sm"></p>
        @unless (request()->secure() || in_array(request()->getHost(), ['localhost', '127.0.0.1']))
          <p class="rounded-lg bg-amber-50 p-3 text-sm text-amber-900">Passkeys need HTTPS. Open the admin at https://{{ request()->getHost() }}.</p>
        @endunless

        <ul class="divide-y divide-gray-100 rounded-xl border border-gray-200">
          @forelse ($passkeys as $pk)
            <li class="flex items-center justify-between gap-3 p-4">
              <div>
                <p class="font-medium text-gray-900"><i class="fas fa-key mr-1 text-gray-400"></i> {{ $pk->name }}</p>
                <p class="text-xs text-gray-500">Added {{ $pk->created_at->format('d M Y') }} · {{ $pk->last_used_at ? 'Last used ' . $pk->last_used_at->diffForHumans() : 'Not used yet' }}</p>
              </div>
              <form method="POST" action="{{ route('admin.passkeys.destroy', $pk) }}" onsubmit="return confirm('Remove this passkey?')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-600 hover:underline">Remove</button>
              </form>
            </li>
          @empty
            <li class="p-4 text-sm text-gray-500">No passkeys yet.</li>
          @endforelse
        </ul>
      </div>

      @include('backend.partials.passkey-js')
      <script>
        (function () {
          const btn = document.getElementById('addPasskey'), msg = document.getElementById('passkeyMsg');
          const show = (text, ok) => { msg.textContent = text; msg.className = 'text-sm ' + (ok ? 'text-emerald-700' : 'text-red-600'); };
          if (!EGPasskey.supported()) { btn.disabled = true; show('This browser does not support passkeys.', false); return; }
          btn.addEventListener('click', async () => {
            btn.disabled = true;
            try {
              await EGPasskey.register(document.getElementById('passkeyName').value.trim());
              show('Passkey added.', true);
              setTimeout(() => location.reload(), 700);
            } catch (e) { show(e.message, false); btn.disabled = false; }
          });
        })();
      </script>
    @endif
  </div>
</section>
@endsection
