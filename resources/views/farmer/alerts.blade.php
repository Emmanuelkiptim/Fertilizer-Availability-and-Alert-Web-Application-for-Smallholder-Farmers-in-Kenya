
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Farmer Dashboard / Alerts') }}
        </h2>
    </x-slot>
    <div class="container">
        <h2>My Alerts</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered alert-table" style="width:100%;background:#f8fafc;">
                <thead>
                    <tr>
                        <th style="width:40px;"></th>
                        <th>Message</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alerts as $alert)
                        <tr class="@if(!$alert->is_read) alert-unread @endif">
                            <td class="text-center align-middle">@if(!$alert->is_read) 🔔 @else 📨 @endif</td>
                            <td class="align-middle">{!! $alert->message !!}</td>
                            <td class="text-nowrap align-middle">{{ $alert->created_at->diffForHumans() }}</td>
                            <td class="align-middle">
                                @if(!$alert->is_read)
                                    <form action="{{ route('alerts.markAsRead', $alert->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="alert-action-btn" style="padding:4px 10px;font-size:0.95em;">Mark as read</button>
                                    </form>
                                </td>
                                @else
                                    <span class="text-success">Read</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">No alerts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    <style>
        .alert-table, .alert-table th, .alert-table td {
            border: 1.5px solid #000000ff !important;
        }
        .alert-table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .alert-table th, .alert-table td {
            vertical-align: middle !important;
        }
        .alert-table {
            background: #f8fafc;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(37,99,235,0.07);
        }
        .alert-table thead th {
            background: #ffffffff;
            color: #000000ff;
            border: none;
            font-weight: 600;
            font-size: 1.05em;
        }
        .alert-table tbody tr {
            transition: background 0.2s;
        }
        .alert-table tbody tr:hover {
            background: #e0e7ff;
        }
        .alert-unread {
            background: #e0f2fe !important;
        }
        .alert-action-btn {
            display: inline-block;
            padding: 6px 18px;
            background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
            color: #fff !important;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1em;
            font-weight: 600;
            margin-left: 8px;
            box-shadow: 0 2px 8px rgba(37,99,235,0.10);
            border: none;
            transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        }
        .alert-action-btn:hover {
            background: linear-gradient(90deg, #1e40af 0%, #2563eb 100%);
            color: #fff !important;
            box-shadow: 0 4px 16px rgba(30,64,175,0.15);
            transform: translateY(-2px) scale(1.04);
        }
        .text-success {
            color: #22c55e;
            font-weight: 600;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</div>
<style>
        .alert-action-btn {
            display: inline-block;
            padding: 6px 18px;
            background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
            color: #fff !important;
            border-radius: 6px;
            text-decoration: none;
            font-size: 1em;
            font-weight: 600;
            margin-left: 8px;
            box-shadow: 0 2px 8px rgba(37,99,235,0.10);
            border: none;
            transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        }
        .alert-action-btn:hover {
            background: linear-gradient(90deg, #1e40af 0%, #2563eb 100%);
            color: #fff !important;
            box-shadow: 0 4px 16px rgba(30,64,175,0.15);
            transform: translateY(-2px) scale(1.04);
        }
        .alert-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .alert-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 8px;
            padding: 12px 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border-left: 5px solid #3b82f6;
            transition: background 0.2s;
        }
        .alert-unread {
            background: #e0f2fe;
            border-left-color: #f59e42;
        }
        .alert-icon {
            font-size: 1.3em;
            margin-right: 10px;
        }
        .alert-message {
            flex: 1;
            margin-right: 10px;
        }
        .alert-time {
            color: #64748b;
            font-size: 0.9em;
            white-space: nowrap;
        }
    </style>
</x-app-layout>
