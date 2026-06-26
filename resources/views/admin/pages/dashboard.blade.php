@extends('admin.layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('page_styles')
<style>
  /* Stats Cards */
  .dashboard-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; }
  .stat-card {
    background: white; border: 1px solid #e8ede9; border-radius: 12px;
    padding: 20px; display: flex; align-items: center; gap: 16px;
  }
  .stat-card__icon {
    width: 48px; height: 48px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
  }
  .stat-card__content { flex: 1; }
  .stat-card__label { font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #9ca3af; font-weight: 600; }
  .stat-card__value { font-size: 28px; font-weight: 700; line-height: 1; margin-top: 4px; }
  .stat-card__badge {
    display: inline-block; padding: 2px 8px; border-radius: 12px;
    font-size: 11px; font-weight: 600; margin-top: 6px;
  }
  .stat-card__badge--new { background: #fef3c7; color: #92400e; }

  /* Charts Grid */
  .charts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
  .chart-card {
    background: white; border: 1px solid #e8ede9; border-radius: 12px;
    padding: 20px; display: flex; flex-direction: column;
  }
  .chart-card__header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
  .chart-card__title { font-size: 13px; font-weight: 600; color: #1a2332; text-transform: uppercase; letter-spacing: 0.05em; }
  .chart-card__body { flex: 1; position: relative; min-height: 200px; }
  .chart-card__body canvas { max-height: 200px; }

  /* Recent Items Grid */
  .recent-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
  .recent-card {
    background: white; border: 1px solid #e8ede9; border-radius: 12px;
    overflow: hidden;
  }
  .recent-card__header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px; border-bottom: 1px solid #f3f4f6;
  }
  .recent-card__title { font-size: 14px; font-weight: 600; color: #1a2332; }
  .recent-card__body { padding: 0; }
  .recent-item {
    display: flex; align-items: center; padding: 12px 20px;
    border-bottom: 1px solid #f9fafb; transition: background 0.1s;
  }
  .recent-item:hover { background: #f9fafb; }
  .recent-item:last-child { border-bottom: none; }
  .recent-item__avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: #f3f4f6; display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 600; color: #6b7280; margin-right: 12px;
  }
  .recent-item__content { flex: 1; min-width: 0; }
  .recent-item__name { font-size: 13px; font-weight: 600; color: #1a2332; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .recent-item__meta { font-size: 11px; color: #9ca3af; margin-top: 2px; }
  .recent-item__status { margin-left: 12px; }
  .recent-item__time { font-size: 11px; color: #9ca3af; margin-left: 12px; white-space: nowrap; }

  /* Responsive */
  @media (max-width: 1024px) {
    .charts-grid { grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 768px) {
    .dashboard-stats { grid-template-columns: 1fr 1fr; gap: 12px; }
    .stat-card { padding: 16px; gap: 12px; }
    .stat-card__icon { width: 40px; height: 40px; }
    .stat-card__value { font-size: 24px; }
    .charts-grid { grid-template-columns: 1fr; gap: 16px; }
    .chart-card__body { min-height: 180px; }
    .chart-card__body canvas { max-height: 180px; }
  }
  @media (max-width: 480px) {
    .dashboard-stats { grid-template-columns: 1fr; }
  }
</style>
@endsection

@section('content')

{{-- Stats Row --}}
<div class="dashboard-stats">
  <div class="stat-card">
    <div class="stat-card__icon" style="background: #5a7a7615;">
      <svg viewBox="0 0 24 24" fill="none" stroke="#5a7a76" stroke-width="1.5" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    </div>
    <div class="stat-card__content">
      <div class="stat-card__label">Total Bookings</div>
      <div class="stat-card__value" style="color: #5a7a76;">{{ $stats['bookings_total'] }}</div>
      @if($stats['bookings_new'] > 0)
        <span class="stat-card__badge stat-card__badge--new">{{ $stats['bookings_new'] }} new</span>
      @endif
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-card__icon" style="background: #f59e0b15;">
      <svg viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="stat-card__content">
      <div class="stat-card__label">Pending</div>
      <div class="stat-card__value" style="color: #f59e0b;">{{ $stats['bookings_new'] }}</div>
    </div>
  </div>

  {{-- Commented out: Contact Messages stat (non-booking module)
  <div class="stat-card">
    <div class="stat-card__icon" style="background: #10b98115;">
      <svg viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1.5" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    </div>
    <div class="stat-card__content">
      <div class="stat-card__label">Contact Messages</div>
      <div class="stat-card__value" style="color: #10b981;">{{ $stats['contacts_total'] }}</div>
      @if($stats['contacts_new'] > 0)
        <span class="stat-card__badge stat-card__badge--new">{{ $stats['contacts_new'] }} unread</span>
      @endif
    </div>
  </div>
  --}}

  {{-- Commented out: Testimonials stat (non-booking module)
  <div class="stat-card">
    <div class="stat-card__icon" style="background: #6366f115;">
      <svg viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.5" width="24" height="24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
    </div>
    <div class="stat-card__content">
      <div class="stat-card__label">Testimonials</div>
      <div class="stat-card__value" style="color: #6366f1;">{{ $stats['testimonials'] }}</div>
    </div>
  </div>
  --}}
</div>

{{-- Charts Section --}}
<div class="charts-grid">
  <div class="chart-card">
    <div class="chart-card__header">
      <span class="chart-card__title">Status Distribution</span>
    </div>
    <div class="chart-card__body">
      <canvas id="statusChart"></canvas>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__header">
      <span class="chart-card__title">Bookings (Last 30 Days)</span>
    </div>
    <div class="chart-card__body">
      <canvas id="timelineChart"></canvas>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-card__header">
      <span class="chart-card__title">Session Types</span>
    </div>
    <div class="chart-card__body">
      <canvas id="sessionTypeChart"></canvas>
    </div>
  </div>
</div>

{{-- Recent Items --}}
<div class="recent-grid">
  {{-- Recent Bookings --}}
  <div class="recent-card">
    <div class="recent-card__header">
      <span class="recent-card__title">Recent Bookings</span>
      <a href="{{ route('admin.bookings.index') }}" class="btn-admin btn-admin--outline" style="font-size: 12px; padding: 6px 12px;">View All</a>
    </div>
    <div class="recent-card__body">
      @if($recentBookings->isEmpty())
        <div style="padding: 40px 20px; text-align: center; color: #9ca3af;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40" style="margin: 0 auto 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/></svg>
          <p style="margin: 0; font-size: 13px;">No bookings yet</p>
        </div>
      @else
        @foreach($recentBookings as $booking)
        <a href="{{ route('admin.bookings.show', $booking) }}" style="text-decoration: none; color: inherit;">
          <div class="recent-item">
            <div class="recent-item__avatar">{{ substr($booking->full_name, 0, 1) }}</div>
            <div class="recent-item__content">
              <div class="recent-item__name">{{ $booking->full_name }}</div>
              <div class="recent-item__meta">{{ $booking->email }}</div>
            </div>
            <div class="recent-item__status">
              @php
                $colors = ['pending'=>'#f59e0b','confirmed'=>'#10b981','cancelled'=>'#ef4444','completed'=>'#6366f1','no_show'=>'#9ca3af'];
                $color = $colors[$booking->status] ?? '#9ca3af';
              @endphp
              <span style="display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;background:{{ $color }}18;color:{{ $color }};">
                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
              </span>
            </div>
            <div class="recent-item__time">{{ $booking->created_at->diffForHumans() }}</div>
          </div>
        </a>
        @endforeach
      @endif
    </div>
  </div>

  {{-- Commented out: Recent Messages (non-booking module)
  <div class="recent-card">
    <div class="recent-card__header">
      <span class="recent-card__title">Recent Messages</span>
      <a href="{{ route('admin.contacts.index') }}" class="btn-admin btn-admin--outline" style="font-size: 12px; padding: 6px 12px;">View All</a>
    </div>
    <div class="recent-card__body">
      @if($recentContacts->isEmpty())
        <div style="padding: 40px 20px; text-align: center; color: #9ca3af;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="40" height="40" style="margin: 0 auto 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
          <p style="margin: 0; font-size: 13px;">No messages yet</p>
        </div>
      @else
        @foreach($recentContacts as $contact)
        <a href="{{ route('admin.contacts.show', $contact) }}" style="text-decoration: none; color: inherit;">
          <div class="recent-item">
            <div class="recent-item__avatar">{{ substr($contact->name, 0, 1) }}</div>
            <div class="recent-item__content">
              <div class="recent-item__name">{{ $contact->name }}</div>
              <div class="recent-item__meta">{{ $contact->email }}</div>
            </div>
            <div class="recent-item__time">{{ $contact->created_at->diffForHumans() }}</div>
          </div>
        </a>
        @endforeach
      @endif
    </div>
  </div>
  --}}
</div>

@endsection

@section('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Chart.js Defaults
Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, sans-serif";
Chart.defaults.color = '#6b7280';

// Status Distribution Chart (Donut)
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
  type: 'doughnut',
  data: {
    labels: @json($statusChartData['labels']),
    datasets: [{
      data: @json($statusChartData['data']),
      backgroundColor: ['#f59e0b', '#10b981', '#6366f1', '#ef4444', '#9ca3af'],
      borderWidth: 0,
      hoverOffset: 4
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: { padding: 12, usePointStyle: true, pointStyle: 'circle', font: { size: 11 } }
      }
    },
    cutout: '65%'
  }
});

// Bookings Over Time Chart (Line)
const timelineCtx = document.getElementById('timelineChart').getContext('2d');
new Chart(timelineCtx, {
  type: 'line',
  data: {
    labels: @json(array_column($bookingsOverTime, 'date')),
    datasets: [{
      label: 'Bookings',
      data: @json(array_column($bookingsOverTime, 'count')),
      borderColor: '#5a9e97',
      backgroundColor: 'rgba(90, 158, 151, 0.1)',
      borderWidth: 2,
      fill: true,
      tension: 0.4,
      pointRadius: 3,
      pointHoverRadius: 5
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { stepSize: 1, font: { size: 11 } },
        grid: { color: '#f3f4f6' }
      },
      x: {
        ticks: { maxTicksLimit: 10, font: { size: 11 } },
        grid: { display: false }
      }
    }
  }
});

// Session Types Chart (Bar)
const sessionTypeCtx = document.getElementById('sessionTypeChart').getContext('2d');
new Chart(sessionTypeCtx, {
  type: 'bar',
  data: {
    labels: @json($sessionTypeChartData['labels']),
    datasets: [{
      data: @json($sessionTypeChartData['data']),
      backgroundColor: ['#5a9e97', '#6366f1', '#f59e0b', '#10b981'],
      borderRadius: 6,
      borderSkipped: false
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { stepSize: 1, font: { size: 11 } },
        grid: { color: '#f3f4f6' }
      },
      x: {
        ticks: { font: { size: 11 } },
        grid: { display: false }
      }
    }
  }
});
</script>
@endsection
