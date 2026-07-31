@extends('admin.layouts.admin')
@section('title', 'Profile')
@section('page_title', 'Profile')

@section('content')

<div style="max-width: 600px;">
  <div class="admin-form">
    <div class="admin-form__section">
      <div class="admin-form__section-title">Account Information</div>

      <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="admin-field">
          <label class="admin-label">Name</label>
          <input type="text" name="name" class="admin-input" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="admin-field">
          <label class="admin-label">Email</label>
          <input type="email" name="email" class="admin-input" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="admin-field">
          <label class="admin-label">Role</label>
          <input type="text" class="admin-input" value="{{ $user->is_super_admin ? 'Super Admin' : 'Admin' }}" disabled style="background: #f9fafb; color: #6b7280;">
        </div>

        <div class="admin-field">
          <label class="admin-label">Member Since</label>
          <input type="text" class="admin-input" value="{{ $user->created_at->format('j F Y') }}" disabled style="background: #f9fafb; color: #6b7280;">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 8px;">
          <button type="submit" class="btn-admin btn-admin--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            Save Changes
          </button>
          <a href="{{ route('admin.password.edit') }}" class="btn-admin btn-admin--outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
            Change Password
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
