<x-guest-layout>
    <div class="text-center mb-4">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,#4a90d9,#1a3c5e);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;margin:0 auto 14px;box-shadow:0 6px 20px rgba(26,60,94,0.3);">
            <i class="fas fa-key"></i>
        </div>
        <h4 style="font-size:1.35rem;font-weight:800;color:#1a3c5e;margin-bottom:4px;">Forgot Password</h4>
        <p style="font-size:0.85rem;color:#8a9bb0;margin:0;">
            No problem — enter your email and we'll send you a reset link.
        </p>
    </div>

    @if (session('status'))
        <div class="alert" style="background:rgba(86,204,157,0.1);border:none;border-left:4px solid #22c55e;border-radius:8px;padding:12px 14px;font-size:0.875rem;color:#166534;margin-bottom:20px;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert" style="background:rgba(220,53,69,0.08);border:none;border-left:4px solid #ef4444;border-radius:8px;padding:12px 14px;font-size:0.875rem;color:#b91c1c;margin-bottom:20px;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group mb-4">
            <label for="email" style="font-weight:600;font-size:0.82rem;color:#5a6e7f;margin-bottom:6px;display:block;">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="your@email.com"
                style="border-color:#dde4ed;border-radius:8px;font-size:0.875rem;padding:10px 14px;">
        </div>

        <button type="submit" class="btn btn-block"
            style="background:linear-gradient(135deg,#4a90d9,#1a3c5e);color:#fff;border:none;border-radius:10px;padding:12px;font-size:0.95rem;font-weight:700;letter-spacing:0.3px;box-shadow:0 4px 16px rgba(26,60,94,0.25);">
            <i class="fas fa-paper-plane mr-2"></i> Email Password Reset Link
        </button>
    </form>

    <div class="text-center mt-4" style="border-top:1px solid #f0f2f5;padding-top:20px;">
        <a href="{{ route('login') }}" style="font-size:0.82rem;color:#4a90d9;font-weight:600;text-decoration:none;">
            <i class="fas fa-arrow-left mr-1"></i> Back to Sign In
        </a>
    </div>
</x-guest-layout>
