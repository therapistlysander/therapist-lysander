@extends('admin.layouts.admin')
@section('title', 'Change Password')
@section('page_title', 'Change Password')

@section('content')

<div class="admin-form" style="max-width:500px;">
    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        @method('PATCH')

        <div class="admin-form__section">
            <div class="admin-form__section-title">Update Your Password</div>

            @if($errors->any())
                <div class="admin-alert admin-alert--error" style="margin-bottom:16px;">{{ $errors->first() }}</div>
            @endif

            <div class="admin-field">
                <label class="admin-label" for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="admin-input" required autocomplete="current-password">
            </div>

            <div class="admin-field">
                <label class="admin-label" for="password">New Password</label>
                <input type="password" name="password" id="password" class="admin-input" required autocomplete="new-password">
                <small style="font-size:11px;color:#9ca3af;margin-top:4px;display:block;">Minimum 8 characters.</small>
            </div>

            <div class="admin-field">
                <label class="admin-label" for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="admin-input" required autocomplete="new-password">
            </div>
        </div>

        <div class="admin-form__section">
            <button type="submit" class="btn-admin btn-admin--primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Update Password
            </button>
        </div>
    </form>
</div>

@endsection
