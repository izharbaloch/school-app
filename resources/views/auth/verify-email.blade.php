<x-guest-layout>
    <div class="text-center mb-4">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,#4a90d9,#1a3c5e);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;margin:0 auto 14px;box-shadow:0 6px 20px rgba(26,60,94,0.3);">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <h4 style="font-size:1.35rem;font-weight:800;color:#1a3c5e;margin-bottom:4px;">Verify Your Email</h4>
        <p style="font-size:0.85rem;color:#8a9bb0;margin:0;">
            Thanks for signing up! Please verify your email address by clicking the link we just sent you.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert" style="background:rgba(86,204,157,0.1);border:none;border-left:4px solid #22c55e;border-radius:8px;padding:12px 14px;font-size:0.875rem;color:#166534;margin-bottom:20px;">
            <i class="fas fa-check-circle mr-2"></i>
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn"
                style="background:linear-gradient(135deg,#4a90d9,#1a3c5e);color:#fff;border:none;border-radius:10px;padding:10px 18px;font-size:0.875rem;font-weight:700;box-shadow:0 4px 16px rgba(26,60,94,0.25);">
                <i class="fas fa-paper-plane mr-2"></i> Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link" style="font-size:0.82rem;color:#8a9bb0;font-weight:600;">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
