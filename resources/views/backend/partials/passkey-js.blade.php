{{-- Browser side of passkeys (WebAuthn). Server: AdminPasskeyController. --}}
<script>
window.EGPasskey = (function () {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  const toBuf = (s) => {
    s = s.replace(/-/g, '+').replace(/_/g, '/');
    while (s.length % 4) s += '=';
    return Uint8Array.from(atob(s), (c) => c.charCodeAt(0)).buffer;
  };
  const toB64u = (buf) => {
    let bin = '';
    new Uint8Array(buf).forEach((b) => bin += String.fromCharCode(b));
    return btoa(bin).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
  };
  async function post(url, body) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      body: JSON.stringify(body || {}),
      credentials: 'same-origin',
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) throw new Error(data.message || 'Something went wrong. Please try again.');
    return data;
  }
  const friendly = (e) => (e && e.name === 'NotAllowedError')
    ? 'Cancelled, or no passkey for this site on this device.'
    : (e && e.name === 'InvalidStateError') ? 'This device already has a passkey for your account.'
    : (e && e.message) || 'Passkey failed.';

  return {
    supported: () => !!(window.PublicKeyCredential && navigator.credentials),

    async register(name) {
      try {
        const opts = await post(@json(route('admin.passkeys.options')));
        const pk = opts.publicKey;
        pk.challenge = toBuf(pk.challenge);
        pk.user.id = toBuf(pk.user.id);
        (pk.excludeCredentials || []).forEach((c) => c.id = toBuf(c.id));
        delete pk.extensions;
        const cred = await navigator.credentials.create({ publicKey: pk });
        return await post(@json(route('admin.passkeys.store')), {
          name,
          clientDataJSON: toB64u(cred.response.clientDataJSON),
          attestationObject: toB64u(cred.response.attestationObject),
        });
      } catch (e) { throw new Error(friendly(e)); }
    },

    async login() {
      try {
        const opts = await post(@json(route('admin.passkey.options')));
        const pk = opts.publicKey;
        pk.challenge = toBuf(pk.challenge);
        (pk.allowCredentials || []).forEach((c) => c.id = toBuf(c.id));
        delete pk.extensions;
        const cred = await navigator.credentials.get({ publicKey: pk });
        return await post(@json(route('admin.passkey.login')), {
          id: toB64u(cred.rawId),
          clientDataJSON: toB64u(cred.response.clientDataJSON),
          authenticatorData: toB64u(cred.response.authenticatorData),
          signature: toB64u(cred.response.signature),
        });
      } catch (e) { throw new Error(friendly(e)); }
    },
  };
})();
</script>
