<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password | Therapist Lysander</title>
  <link rel="stylesheet" href="/css/site.css">
  <style>
    body { background: var(--color-bg); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: var(--space-6); }
    .login-card { background: var(--color-white); border: 1px solid var(--color-border); border-radius: var(--radius-md); padding: var(--space-10); width: 100%; max-width: 400px; box-shadow: var(--shadow-md); }
    .login-card__logo { text-align: center; margin-bottom: var(--space-8); }
    .login-card__logo img { height: 40px; }
    .login-card__title { font-family: var(--font-heading); font-size: var(--size-xl); color: var(--color-text); margin-bottom: var(--space-2); text-align: center; }
    .login-card__sub { font-size: var(--size-sm); color: var(--color-text-muted); text-align: center; margin-bottom: var(--space-8); }
    .form-error { background: #fef2f2; border: 1px solid #fecaca; border-radius: var(--radius); padding: var(--space-3) var(--space-4); font-size: var(--size-sm); color: #dc2626; margin-bottom: var(--space-6); }
    .form-success { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius); padding: var(--space-3) var(--space-4); font-size: var(--size-sm); color: #166534; margin-bottom: var(--space-6); }
  </style>
</head>
<body>
  <div class="login-card">
    <div class="login-card__logo">
      <img src="/images/logo.png" alt="Therapist Lysander">
    </div>
    <h1 class="login-card__title">Reset Password</h1>
    <p class="login-card__sub">Enter your email address and we'll send you a password reset link.</p>

    @if(session('success'))
      <div class="form-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
      <div class="form-error">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.password.email') }}">
      @csrf
      <div class="form-group">
        <label class="form-label" for="email">Email address</label>
        <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
      </div>
      <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;margin-top:var(--space-6);">
        Send Reset Link
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
      </button>
    </form>

    <div style="margin-top:var(--space-6);padding-top:var(--space-6);border-top:1px solid var(--color-border);text-align:center;">
      <a href="{{ route('admin.login') }}" style="font-size:var(--size-sm);color:var(--color-text-muted);">
        &larr; Back to login
      </a>
    </div>
  </div>
</body>
</html>
