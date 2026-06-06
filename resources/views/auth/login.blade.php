<x-guest-layout>
    {{-- ============================================================
         LOGIN FORM
         Preserved: form action="{{ route('login') }}", @csrf,
         id="email", name="email", id="password", name="password",
         id="remember_me", name="remember"
         Only the HTML/CSS presentation is changed (Bootstrap classes)
         ============================================================ --}}

    {{-- School logo / page heading --}}
    <div class="text-center mb-4">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,#4a90d9,#1a3c5e);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;margin:0 auto 14px;box-shadow:0 6px 20px rgba(26,60,94,0.3);">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <h4 style="font-size:1.35rem;font-weight:800;color:#1a3c5e;margin-bottom:4px;">Welcome Back</h4>
        <p style="font-size:0.85rem;color:#8a9bb0;margin:0;">Sign in to your EduManage account</p>
    </div>

    {{-- Session status (e.g. password-reset success message) --}}
    @if(session('status'))
        <div class="alert" style="background:rgba(86,204,157,0.1);border:none;border-left:4px solid #22c55e;border-radius:8px;padding:12px 14px;font-size:0.875rem;color:#166534;margin-bottom:20px;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
        </div>
    @endif

    {{-- Validation errors summary --}}
    @if($errors->any())
        <div class="alert" style="background:rgba(220,53,69,0.08);border:none;border-left:4px solid #ef4444;border-radius:8px;padding:12px 14px;font-size:0.875rem;color:#b91c1c;margin-bottom:20px;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email Address --}}
        <div class="form-group mb-3">
            <label for="email" style="font-weight:600;font-size:0.82rem;color:#5a6e7f;margin-bottom:6px;display:block;">
                Email Address
            </label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="background:#f7f9fc;border-color:#dde4ed;border-right:none;border-radius:8px 0 0 8px;">
                        <i class="fas fa-envelope" style="color:#8a9bb0;font-size:0.85rem;"></i>
                    </span>
                </div>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    placeholder="your@email.com"
                    style="border-color:#dde4ed;border-left:none;border-radius:0 8px 8px 0;font-size:0.875rem;padding:10px 14px;"
                >
            </div>
        </div>

        {{-- Password --}}
        <div class="form-group mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1" style="margin-bottom:6px;">
                <label for="password" style="font-weight:600;font-size:0.82rem;color:#5a6e7f;margin:0;">
                    Password
                </label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:0.78rem;color:#4a90d9;font-weight:600;text-decoration:none;">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="background:#f7f9fc;border-color:#dde4ed;border-right:none;border-radius:8px 0 0 8px;">
                        <i class="fas fa-lock" style="color:#8a9bb0;font-size:0.85rem;"></i>
                    </span>
                </div>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="••••••••"
                    style="border-color:#dde4ed;border-left:none;border-radius:0 8px 8px 0;font-size:0.875rem;padding:10px 14px;"
                >
            </div>
        </div>

        {{-- Remember Me --}}
        <div class="d-flex align-items-center mb-4">
            <div class="custom-control custom-checkbox">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="custom-control-input"
                    name="remember"
                >
                <label class="custom-control-label" for="remember_me" style="font-size:0.82rem;color:#5a6e7f;font-weight:500;cursor:pointer;">
                    Keep me signed in
                </label>
            </div>
        </div>

        {{-- Submit Button --}}
        <button
            type="submit"
            class="btn btn-block"
            style="background:linear-gradient(135deg,#4a90d9,#1a3c5e);color:#fff;border:none;border-radius:10px;padding:12px;font-size:0.95rem;font-weight:700;letter-spacing:0.3px;transition:all 0.2s;box-shadow:0 4px 16px rgba(26,60,94,0.25);"
            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(26,60,94,0.35)';"
            onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(26,60,94,0.25)';"
        >
            <i class="fas fa-sign-in-alt mr-2"></i> Sign In to Dashboard
        </button>

    </form>

    {{-- Divider --}}
    <div class="text-center mt-4" style="border-top:1px solid #f0f2f5;padding-top:20px;">
        <p style="font-size:0.8rem;color:#8a9bb0;margin:0;">
            <i class="fas fa-shield-alt mr-1" style="color:#4a90d9;"></i>
            Secured by role-based access control
        </p>
    </div>
</x-guest-layout>
