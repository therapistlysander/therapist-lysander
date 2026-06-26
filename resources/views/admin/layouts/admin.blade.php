<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') | Therapist Lysander CMS</title>
  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
  <link rel="stylesheet" href="/css/site.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css">
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])
  <style>
    *, *::before, *::after { box-sizing: border-box; }
    body { margin: 0; font-family: var(--font-body); background: #f1f3f5; color: var(--color-text); }

    /* Sidebar */
    .admin-sidebar {
      position: fixed; top: 0; left: 0; bottom: 0; width: 240px;
      background: #1a2332; display: flex; flex-direction: column; z-index: 100;
      overflow-y: auto; transition: transform 0.3s ease;
    }
    .admin-sidebar__overlay {
      display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5);
      z-index: 99; opacity: 0; transition: opacity 0.3s ease;
    }
    .admin-sidebar__overlay.visible { display: block; opacity: 1; }
    .admin-sidebar__brand {
      padding: 20px 20px 16px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      display: flex; align-items: center; gap: 10px;
    }
    .admin-sidebar__brand img { height: 28px; filter: brightness(0) invert(1); opacity: 0.9; }
    .admin-sidebar__label { font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.12em; font-weight: 600; }

    .admin-nav { padding: 12px 0; flex: 1; }
    .admin-nav__section { padding: 8px 20px 4px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.14em; color: rgba(255,255,255,0.3); font-weight: 600; }
    .admin-nav__link {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 20px; font-size: 13.5px; color: rgba(255,255,255,0.65);
      text-decoration: none; transition: all 0.15s; border-left: 3px solid transparent;
    }
    .admin-nav__link:hover { color: white; background: rgba(255,255,255,0.05); }
    .admin-nav__link.active { color: white; background: rgba(255,255,255,0.08); border-left-color: #5a9e97; }
    .admin-nav__link svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.7; }
    .admin-nav__link.active svg { opacity: 1; }

    .admin-sidebar__footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(255,255,255,0.08);
      font-size: 12px; color: rgba(255,255,255,0.4);
    }
    .admin-sidebar__footer a { color: rgba(255,255,255,0.4); text-decoration: none; }
    .admin-sidebar__footer a:hover { color: rgba(255,255,255,0.8); }

    /* Main content */
    .admin-main { margin-left: 240px; min-height: 100vh; display: flex; flex-direction: column; }

    .admin-topbar {
      background: white; border-bottom: 1px solid #e5e7eb;
      padding: 0 28px; height: 56px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 50;
    }
    .admin-topbar__title { font-size: 15px; font-weight: 600; color: #1a2332; }
    .admin-topbar__user { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #6b7280; }
    .admin-topbar__avatar {
      width: 32px; height: 32px; border-radius: 50%;
      background: #5a9e97; color: white;
      display: flex; align-items: center; justify-content: center;
      font-size: 13px; font-weight: 600;
    }
    .admin-hamburger {
      display: none; background: none; border: none; cursor: pointer;
      padding: 8px; color: #6b7280; border-radius: 6px; transition: background 0.15s;
    }
    .admin-hamburger:hover { background: #f3f4f6; color: #374151; }
    .admin-hamburger svg { width: 20px; height: 20px; }

    .admin-content { padding: 28px; flex: 1; }

    /* Page header */
    .admin-page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; gap: 16px; flex-wrap: wrap; }
    .admin-page-header h1 { font-size: 22px; font-weight: 700; color: #1a2332; margin: 0; }

    /* Stats cards */
    .admin-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .admin-stat { background: white; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; }
    .admin-stat__num { font-size: 28px; font-weight: 700; color: #1a2332; line-height: 1; margin-bottom: 4px; }
    .admin-stat__label { font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.08em; }
    .admin-stat__badge { display: inline-block; margin-top: 8px; font-size: 11px; padding: 2px 8px; border-radius: 999px; }
    .admin-stat__badge--new { background: #dbeafe; color: #1d4ed8; }
    .admin-stat__badge--pending { background: #fef3c7; color: #92400e; }

    /* Table */
    .admin-table-wrap { background: white; border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
    .admin-table-header { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .admin-table-header h2 { font-size: 15px; font-weight: 600; color: #1a2332; margin: 0; }
    .admin-table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table { width: 100%; border-collapse: collapse; min-width: 600px; }
    thead th { background: #f9fafb; padding: 10px 16px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
    tbody td { padding: 12px 16px; font-size: 13.5px; color: #374151; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    tbody tr:last-child td { border-bottom: none; }
    tbody tr:hover td { background: #f9fafb; }

    /* Badges */
    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 500; }
    .badge--new, .badge--pending { background: #fef3c7; color: #92400e; }
    .badge--confirmed, .badge--active { background: #d1fae5; color: #065f46; }
    .badge--read, .badge--resolved { background: #e0e7ff; color: #3730a3; }
    .badge--cancelled { background: #fee2e2; color: #991b1b; }
    .badge--featured { background: #fce7f3; color: #9d174d; }

    /* Buttons */
    .btn-admin { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; border: none; transition: all 0.15s; }
    .btn-admin--primary { background: #5a9e97; color: white; }
    .btn-admin--primary:hover { background: #4a8880; }
    .btn-admin--outline { background: white; color: #374151; border: 1px solid #d1d5db; }
    .btn-admin--outline:hover { background: #f9fafb; }
    .btn-admin--danger { background: #fee2e2; color: #dc2626; }
    .btn-admin--danger:hover { background: #fecaca; }
    .btn-admin svg { width: 14px; height: 14px; }

    /* Forms */
    .admin-form { background: white; border: 1px solid #e5e7eb; border-radius: 10px; }
    .admin-form__section { padding: 24px; border-bottom: 1px solid #e5e7eb; }
    .admin-form__section:last-child { border-bottom: none; }
    .admin-form__section-title { font-size: 14px; font-weight: 600; color: #1a2332; margin-bottom: 16px; }
    .admin-field { margin-bottom: 16px; }
    .admin-field:last-child { margin-bottom: 0; }
    .admin-label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 5px; }
    .admin-input { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13.5px; color: #1a2332; background: white; transition: border-color 0.15s; outline: none; }
    .admin-input:focus { border-color: #5a9e97; box-shadow: 0 0 0 3px rgba(90,158,151,0.1); }
    textarea.admin-input { resize: vertical; min-height: 100px; }
    .admin-select { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13.5px; color: #1a2332; background: white; }

    /* Alert */
    .admin-alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; }
    .admin-alert--success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
    .admin-alert--error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* Empty state */
    .admin-empty { text-align: center; padding: 48px 24px; color: #9ca3af; }
    .admin-empty svg { width: 40px; height: 40px; margin: 0 auto 12px; opacity: 0.4; }
    .admin-empty p { font-size: 14px; }

    /* Detail card */
    .admin-detail { background: white; border: 1px solid #e5e7eb; border-radius: 10px; padding: 24px; }
    .admin-detail__row { display: flex; gap: 16px; padding: 10px 0; border-bottom: 1px solid #f3f4f6; }
    .admin-detail__row:last-child { border-bottom: none; }
    .admin-detail__label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; width: 140px; flex-shrink: 0; padding-top: 1px; }
    .admin-detail__value { font-size: 13.5px; color: #1a2332; flex: 1; }

    /* Responsive */
    @media (max-width: 1024px) {
      .admin-content { padding: 20px; }
      .admin-stats { grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); }
    }

    @media (max-width: 768px) {
      .admin-hamburger { display: block; }
      .admin-sidebar { transform: translateX(-100%); }
      .admin-sidebar.open { transform: translateX(0); }
      .admin-main { margin-left: 0; }
      .admin-topbar { padding: 0 16px; }
      .admin-content { padding: 16px; }
      .admin-page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
      .admin-page-header h1 { font-size: 18px; }
      .admin-stats { grid-template-columns: 1fr 1fr; gap: 12px; }
      .admin-stat { padding: 16px; }
      .admin-stat__num { font-size: 24px; }
      .admin-table-header { flex-direction: column; align-items: stretch; gap: 8px; }
      .admin-table-header form { width: 100%; }
      table { font-size: 12px; }
      thead th, tbody td { padding: 8px 10px; }
      .admin-form__section { padding: 16px; }
      .admin-detail { padding: 16px; }
      .admin-detail__row { flex-direction: column; gap: 4px; }
      .admin-detail__label { width: 100%; }
      .admin-notif__dropdown { width: 280px; right: -60px; }
      .admin-user-menu__btn span { display: none; }
    }

    @media (max-width: 480px) {
      .admin-stats { grid-template-columns: 1fr; }
      .admin-topbar__user { gap: 6px; }
      .admin-notif__dropdown { width: 260px; right: -40px; }
      .confirm-modal__content { padding: 20px; }
      .confirm-modal__actions { flex-direction: column; }
      .confirm-modal__actions .btn-admin { width: 100%; }
    }

    /* Rich text editor (Quill) */
    .admin-editor-wrap { border: 1px solid #d1d5db; border-radius: 8px; overflow: hidden; background: white; }
    .admin-editor-wrap .ql-toolbar { border: none; border-bottom: 1px solid #e5e7eb; background: #f9fafb; padding: 8px 12px; }
    .admin-editor-wrap .ql-toolbar .ql-formats { margin-right: 12px; }
    .admin-editor-wrap .ql-container { border: none; font-size: 13.5px; font-family: inherit; }
    .admin-editor-wrap .ql-editor { min-height: 180px; padding: 14px 16px; line-height: 1.7; color: #1a2332; }
    .admin-editor-wrap .ql-editor.ql-blank::before { font-style: normal; color: #9ca3af; }
    .admin-editor-wrap .ql-editor p { margin-bottom: 0.75em; }
    .admin-editor-wrap .ql-editor h1, .admin-editor-wrap .ql-editor h2, .admin-editor-wrap .ql-editor h3 { margin-top: 0.5em; margin-bottom: 0.4em; }
    .admin-editor-wrap:focus-within { border-color: #5a9e97; box-shadow: 0 0 0 3px rgba(90,158,151,0.1); }

    /* Notification Bell */
    .admin-notif { position: relative; }
    .admin-notif__btn {
      position: relative; background: none; border: none; cursor: pointer; padding: 6px;
      color: #6b7280; border-radius: 6px; transition: background 0.15s;
    }
    .admin-notif__btn:hover { background: #f3f4f6; color: #374151; }
    .admin-notif__badge {
      position: absolute; top: 2px; right: 2px; min-width: 16px; height: 16px;
      background: #ef4444; color: #fff; font-size: 10px; font-weight: 600;
      border-radius: 99px; display: flex; align-items: center; justify-content: center;
      padding: 0 4px; line-height: 1;
    }
    .admin-notif__dropdown {
      display: none; position: absolute; top: calc(100% + 8px); right: 0;
      width: 340px; background: #fff; border: 1px solid #e5e7eb;
      border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.12);
      z-index: 200; overflow: hidden;
    }
    .admin-notif__dropdown.open { display: block; }
    .admin-notif__header {
      padding: 12px 16px; border-bottom: 1px solid #e5e7eb;
      display: flex; align-items: center; justify-content: space-between;
      font-size: 13px; font-weight: 600; color: #1a2332;
    }
    .admin-notif__list { max-height: 320px; overflow-y: auto; }
    .admin-notif__item {
      display: flex; gap: 10px; padding: 12px 16px; border-bottom: 1px solid #f3f4f6;
      text-decoration: none; color: inherit; transition: background 0.1s;
    }
    .admin-notif__item:hover { background: #f9fafb; }
    .admin-notif__item.unread { background: #f0fdf9; }
    .admin-notif__item-icon {
      width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center; font-size: 14px;
    }
    .admin-notif__item-icon--booking { background: #dbeafe; color: #2563eb; }
    .admin-notif__item-icon--contact { background: #fef3c7; color: #d97706; }
    .admin-notif__item-body { flex: 1; min-width: 0; }
    .admin-notif__item-text { font-size: 12.5px; color: #374151; margin: 0; line-height: 1.4; }
    .admin-notif__item-time { font-size: 11px; color: #9ca3af; margin: 2px 0 0; }
    .admin-notif__empty { padding: 32px 16px; text-align: center; font-size: 13px; color: #9ca3af; }

    /* User Dropdown */
    .admin-user-menu { position: relative; }
    .admin-user-menu__btn {
      display: flex; align-items: center; gap: 8px; background: none; border: none;
      cursor: pointer; padding: 4px 8px; border-radius: 8px; transition: background 0.15s;
      font-size: 13px; color: #6b7280;
    }
    .admin-user-menu__btn:hover { background: #f3f4f6; }
    .admin-user-menu__dropdown {
      display: none; position: absolute; top: calc(100% + 8px); right: 0;
      width: 200px; background: #fff; border: 1px solid #e5e7eb;
      border-radius: 10px; box-shadow: 0 10px 40px rgba(0,0,0,0.12);
      z-index: 200; overflow: hidden; padding: 4px;
    }
    .admin-user-menu__dropdown.open { display: block; }
    .admin-user-menu__item {
      display: flex; align-items: center; gap: 8px; width: 100%;
      padding: 9px 12px; font-size: 13px; color: #374151; text-decoration: none;
      border: none; background: none; cursor: pointer; border-radius: 6px;
      transition: background 0.1s; text-align: left;
    }
    .admin-user-menu__item:hover { background: #f3f4f6; }
    .admin-user-menu__item--danger { color: #dc2626; }
    .admin-user-menu__item--danger:hover { background: #fef2f2; }

    /* Confirmation Modal */
    .confirm-modal { display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; }
    .confirm-modal.visible { display: flex; }
    .confirm-modal__backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(2px); }
    .confirm-modal__content {
      position: relative; background: white; border-radius: 12px; padding: 28px; max-width: 420px; width: 90%;
      box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 1;
    }
    .confirm-modal__icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .confirm-modal__icon--danger { background: #fee2e2; color: #ef4444; }
    .confirm-modal__icon--warning { background: #fef3c7; color: #f59e0b; }
    .confirm-modal__icon--info { background: #dbeafe; color: #3b82f6; }
    .confirm-modal__title { font-size: 16px; font-weight: 600; color: #1a2332; text-align: center; margin: 0 0 8px; }
    .confirm-modal__text { font-size: 13px; color: #6b7280; text-align: center; line-height: 1.6; margin: 0 0 24px; }
    .confirm-modal__actions { display: flex; gap: 12px; justify-content: center; }
    .confirm-modal__actions .btn-admin { min-width: 120px; justify-content: center; }
  </style>
  @yield('page_styles')
</head>
<body>

<!-- Sidebar Overlay -->
<div class="admin-sidebar__overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="admin-sidebar" id="admin-sidebar">
  <div class="admin-sidebar__brand">
    <img src="/images/logo.png" alt="Therapist Lysander">
  </div>
  <p class="admin-sidebar__label" style="padding:12px 20px 0;">CMS Admin</p>

  @php $isSuperAdmin = auth()->user()?->isSuperAdmin(); @endphp

  <nav class="admin-nav">
    <p class="admin-nav__section">Overview</p>
    <a href="{{ route('admin.dashboard') }}" class="admin-nav__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
      Dashboard
    </a>

    <p class="admin-nav__section">Requests</p>
    <a href="{{ route('admin.bookings.index') }}" class="admin-nav__link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
      Bookings
    </a>
    @if($isSuperAdmin)
    <a href="{{ route('admin.contacts.index') }}" class="admin-nav__link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
      Contact Messages
    </a>
    @endif

    @if($isSuperAdmin)
    <p class="admin-nav__section">Content</p>
    <a href="{{ route('admin.testimonials.index') }}" class="admin-nav__link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
      Testimonials
    </a>
    <a href="{{ route('admin.faqs.index') }}" class="admin-nav__link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
      FAQs
    </a>
    <a href="{{ route('admin.pages.index') }}" class="admin-nav__link {{ request()->routeIs('admin.pages.*') || request()->routeIs('admin.sections.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
      Pages
    </a>
    <a href="{{ route('admin.seo.index') }}" class="admin-nav__link {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
      SEO Settings
    </a>
    <a href="{{ route('admin.media.index') }}" class="admin-nav__link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
      Media
    </a>
    @endif

    <p class="admin-nav__section">Scheduling</p>
    <a href="{{ route('admin.availability.index') }}" class="admin-nav__link {{ request()->routeIs('admin.availability.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      Availability
    </a>

    <p class="admin-nav__section">Configuration</p>
    <a href="{{ route('admin.settings.index') }}" class="admin-nav__link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      Site Settings
    </a>
    @if($isSuperAdmin)
    <a href="{{ route('admin.ui-translations.index') }}" class="admin-nav__link {{ request()->routeIs('admin.ui-translations.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25v3.75m-3.334.064A48.126 48.126 0 019 9c1.12 0 2.233.038 3.334.114m0 0v7.65"/></svg>
      UI Translations
    </a>
    @endif
    <a href="{{ route('admin.email-settings.index') }}" class="admin-nav__link {{ request()->routeIs('admin.email-settings.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V18z"/></svg>
      Email & Notifications
    </a>
    <a href="{{ route('admin.google-calendar.index') }}" class="admin-nav__link {{ request()->routeIs('admin.google-calendar.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
      Google Calendar Settings
    </a>
  </nav>

  <div class="admin-sidebar__footer">
    <a href="{{ route('home') }}" target="_blank">View website &rarr;</a><br>
    <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:8px;">
      @csrf
      <button type="submit" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.4);font-size:12px;padding:0;">Sign out</button>
    </form>
  </div>
</aside>

<!-- Main -->
<div class="admin-main">
  <header class="admin-topbar">
    <div style="display:flex;align-items:center;gap:12px;">
      <button type="button" class="admin-hamburger" id="hamburger-btn" onclick="toggleSidebar()" title="Toggle menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
      </button>
      <span class="admin-topbar__title">@yield('page_title', 'Dashboard')</span>
    </div>
    <div class="admin-topbar__user">
      {{-- Notification Bell --}}
      <div class="admin-notif" id="notif-wrap">
        <button type="button" class="admin-notif__btn" id="notif-btn" title="Notifications">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
          <span class="admin-notif__badge" id="notif-badge" style="display:none;">0</span>
        </button>
        <div class="admin-notif__dropdown" id="notif-dropdown">
          <div class="admin-notif__header">
            <span>Notifications</span>
            <a href="#" id="notif-mark-all" style="font-size:11px;color:#5a9e97;text-decoration:none;">Mark all read</a>
          </div>
          <div class="admin-notif__list" id="notif-list">
            <div class="admin-notif__empty">No new notifications</div>
          </div>
        </div>
      </div>

      {{-- User Dropdown --}}
      <div class="admin-user-menu" id="user-menu-wrap">
        <button type="button" class="admin-user-menu__btn" id="user-menu-btn">
          <span>{{ Auth::user()->name }}</span>
          <div class="admin-topbar__avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
        </button>
        <div class="admin-user-menu__dropdown" id="user-menu-dropdown">
          <a href="{{ route('admin.profile') }}" class="admin-user-menu__item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            Profile
          </a>
          <a href="{{ route('admin.password.edit') }}" class="admin-user-menu__item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
            Change Password
          </a>
          <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="admin-user-menu__item admin-user-menu__item--danger">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
              Sign Out
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>

  <div class="admin-content">
    @if(session('success'))
      <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="admin-alert admin-alert--error">{{ session('error') }}</div>
    @endif

    @yield('content')
  </div>
</div>

{{-- Shared Confirmation Modal --}}
<div class="confirm-modal" id="confirm-modal">
  <div class="confirm-modal__backdrop" onclick="closeConfirmModal()"></div>
  <div class="confirm-modal__content">
    <div class="confirm-modal__icon confirm-modal__icon--danger" id="confirm-modal-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
    </div>
    <h3 class="confirm-modal__title" id="confirm-modal-title">Are you sure?</h3>
    <p class="confirm-modal__text" id="confirm-modal-text">This action cannot be undone.</p>
    <div class="confirm-modal__actions">
      <button type="button" class="btn-admin btn-admin--secondary" onclick="closeConfirmModal()">Cancel</button>
      <button type="button" class="btn-admin btn-admin--danger" id="confirm-modal-confirm">Confirm</button>
    </div>
  </div>
</div>

@yield('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
// Auto-init all rich text editors on the page
document.querySelectorAll('[data-editor]').forEach(function(wrap) {
  const target = wrap.dataset.editor; // hidden input/textarea ID
  const hiddenEl = document.getElementById(target);
  if (!hiddenEl) return;

  const editorDiv = wrap.querySelector('.ql-editor-area');
  const quill = new Quill(editorDiv, {
    theme: 'snow',
    placeholder: wrap.dataset.placeholder || 'Start writing...',
    modules: {
      toolbar: [
        [{ header: [2, 3, false] }],
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['link'],
        ['clean']
      ]
    }
  });

  // Store instance on wrap for external access (e.g. translation pre-fill)
  wrap.__quill = quill;

  // Set initial content
  if (hiddenEl.value) {
    quill.root.innerHTML = hiddenEl.value;
  }

  // Strip alignment classes and normalize <br> to <p> before saving
  function cleanEditorHtml(html) {
    // Remove alignment classes (force left-align)
    html = html.replace(/\s*class\s*=\s*"ql-align-(center|right|justify)"/gi, '');
    // Convert <br><br> (or <br> with whitespace) to paragraph breaks
    html = html.replace(/(<br\s*\/?>\s*){2,}/gi, '</p><p>');
    // Wrap loose text in <p> if needed
    if (html && !html.startsWith('<p>') && !html.startsWith('<h')) {
      html = '<p>' + html + '</p>';
    }
    return html;
  }

  // Sync editor content to hidden input on change
  quill.on('text-change', function() {
    const html = cleanEditorHtml(quill.root.innerHTML);
    hiddenEl.value = (html === '<p><br></p>') ? '' : html;
  });

  // Also sync before form submission
  const form = wrap.closest('form');
  if (form) {
    form.addEventListener('submit', function() {
      const html = cleanEditorHtml(quill.root.innerHTML);
      hiddenEl.value = (html === '<p><br></p>') ? '' : html;
    });
  }
});
</script>
<script>
// Notification & User Menu Dropdowns
(function() {
  const notifBtn = document.getElementById('notif-btn');
  const notifDropdown = document.getElementById('notif-dropdown');
  const userMenuBtn = document.getElementById('user-menu-btn');
  const userMenuDropdown = document.getElementById('user-menu-dropdown');
  const notifBadge = document.getElementById('notif-badge');
  const notifList = document.getElementById('notif-list');
  const markAllBtn = document.getElementById('notif-mark-all');

  function closeAll() {
    notifDropdown.classList.remove('open');
    userMenuDropdown.classList.remove('open');
  }

  notifBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    userMenuDropdown.classList.remove('open');
    notifDropdown.classList.toggle('open');
    if (notifDropdown.classList.contains('open')) loadNotifications();
  });

  userMenuBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    notifDropdown.classList.remove('open');
    userMenuDropdown.classList.toggle('open');
  });

  document.addEventListener('click', function(e) {
    if (!e.target.closest('.admin-notif') && !e.target.closest('.admin-user-menu')) {
      closeAll();
    }
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeAll();
  });

  // Load recent notifications (bookings + contacts)
  function loadNotifications() {
    fetch('/admin/notifications/recent', {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(data => {
      renderNotifications(data.notifications || []);
      updateBadge(data.unread_count || 0);
    })
    .catch(() => {});
  }

  function renderNotifications(items) {
    if (items.length === 0) {
      notifList.innerHTML = '<div class="admin-notif__empty">No new notifications</div>';
      return;
    }
    notifList.innerHTML = items.map(item => `
      <a href="${item.url}" class="admin-notif__item ${item.is_read ? '' : 'unread'}">
        <div class="admin-notif__item-icon admin-notif__item-icon--${item.type}">${item.icon}</div>
        <div class="admin-notif__item-body">
          <p class="admin-notif__item-text">${item.text}</p>
          <p class="admin-notif__item-time">${item.time}</p>
        </div>
      </a>
    `).join('');
  }

  function updateBadge(count) {
    if (count > 0) {
      notifBadge.textContent = count > 99 ? '99+' : count;
      notifBadge.style.display = 'flex';
    } else {
      notifBadge.style.display = 'none';
    }
  }

  markAllBtn.addEventListener('click', function(e) {
    e.preventDefault();
    fetch('/admin/notifications/mark-read', {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(() => {
      notifList.querySelectorAll('.unread').forEach(el => el.classList.remove('unread'));
      updateBadge(0);
    })
    .catch(() => {});
  });

  // Initial badge count load
  fetch('/admin/notifications/recent', {
    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
  })
  .then(r => r.json())
  .then(data => updateBadge(data.unread_count || 0))
  .catch(() => {});
})();

// Confirmation Modal Functions
function showConfirmModal(title, message, onConfirm, type = 'danger') {
  const modal = document.getElementById('confirm-modal');
  const icon = document.getElementById('confirm-modal-icon');
  const titleEl = document.getElementById('confirm-modal-title');
  const textEl = document.getElementById('confirm-modal-text');
  const confirmBtn = document.getElementById('confirm-modal-confirm');

  titleEl.textContent = title;
  textEl.textContent = message;

  // Update icon style
  icon.className = 'confirm-modal__icon confirm-modal__icon--' + type;

  // Update confirm button style
  confirmBtn.className = 'btn-admin btn-admin--' + (type === 'danger' ? 'danger' : 'primary');

  // Set confirm action
  confirmBtn.onclick = function() {
    closeConfirmModal();
    if (onConfirm) onConfirm();
  };

  modal.classList.add('visible');
}

function closeConfirmModal() {
  document.getElementById('confirm-modal').classList.remove('visible');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeConfirmModal();
});

// Sidebar Toggle
function toggleSidebar() {
  const sidebar = document.getElementById('admin-sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  sidebar.classList.toggle('open');
  overlay.classList.toggle('visible');
}

// Close sidebar when clicking nav links on mobile
document.querySelectorAll('.admin-nav__link').forEach(function(link) {
  link.addEventListener('click', function() {
    if (window.innerWidth <= 768) {
      const sidebar = document.getElementById('admin-sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.remove('open');
      overlay.classList.remove('visible');
    }
  });
});
</script>
</body>
</html>
