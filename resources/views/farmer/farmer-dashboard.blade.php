<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Farmer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-2">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="container py-4">
        <h2 class="font-bold mb-3">My Alerts</h2>
        <div class="mb-3">
            <a href="{{ route('alerts.index') }}" class="alert-action-btn">Go to All Alerts</a>
        </div>
        <ul class="alert-list">
            @forelse($alerts as $alert)
                <li class="alert-item @if(!$alert->is_read) alert-unread @endif">
                    <span class="alert-icon">@if(!$alert->is_read) 🔔 @else 📨 @endif</span>
                    <span class="alert-message">{!! $alert->message !!}</span>
                    <span class="alert-time">{{ $alert->created_at->diffForHumans() }}</span>
                    @if(!$alert->is_read)
                        <form action="{{ route('alerts.markAsRead', $alert->id) }}" method="POST" style="display:inline; margin-left:10px;">
                            @csrf
                            <button class="btn btn-sm btn-primary">Mark as read</button>
                        </form>
                    @endif
                </li>
            @empty
                <li class="alert-item">No alerts found.</li>
            @endforelse
        </ul>
    </div>
        </div>
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
