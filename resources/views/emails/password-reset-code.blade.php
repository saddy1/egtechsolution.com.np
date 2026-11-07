<!doctype html>
<html>
<body style="margin:0;padding:24px;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#0f172a">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:480px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden">
    <tr><td style="background:#002e5b;padding:20px 24px;color:#ffffff;font-size:18px;font-weight:bold">EG Tech Solution</td></tr>
    <tr><td style="padding:24px">
      <p style="margin:0 0 12px">Hi{{ $name ? ' ' . $name : '' }},</p>
      <p style="margin:0 0 20px">Use this code to reset your {{ $isAdmin ? 'admin ' : '' }}password:</p>
      <p style="margin:0 0 20px;font-size:34px;letter-spacing:8px;font-weight:bold;text-align:center;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px">{{ $code }}</p>
      <p style="margin:0 0 8px;font-size:14px;color:#475569">The code expires in {{ \App\Services\PasswordResetCodes::TTL_MINUTES }} minutes.</p>
      <p style="margin:0;font-size:14px;color:#475569">If you didn't ask for this, ignore this email. Your password won't change.</p>
    </td></tr>
  </table>
</body>
</html>
