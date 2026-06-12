@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<div class="section-header">
    <h1>My Profile</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Profile</div>
    </div>
</div>

<div class="section-body">
    <div class="row">

        {{-- Profile Information --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-user mr-2"></i>Profile Information</h4>
                </div>
                <div class="card-body">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Profile updated successfully.
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name"
                                value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $user->roles->pluck('name')->map(fn($r) => ucwords($r))->join(', ') ?: 'No role assigned' }}"
                                readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Update Password --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-lock mr-2"></i>Update Password</h4>
                </div>
                <div class="card-body">
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            Password updated successfully.
                        </div>
                    @endif

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password"
                                class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif"
                                autocomplete="current-password">
                            @if ($errors->updatePassword->has('current_password'))
                                <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="password"
                                class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif"
                                autocomplete="new-password">
                            @if ($errors->updatePassword->has('password'))
                                <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                                autocomplete="new-password">
                            @if ($errors->updatePassword->has('password_confirmation'))
                                <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key mr-1"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
