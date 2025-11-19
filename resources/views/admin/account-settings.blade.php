@extends('adminlte::page')

@section('title', 'Admin Account Settings')

@section('content_header')
    <h1>Admin Account Settings</h1>
@stop

@section('content')
<div class="row mb-4">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Account Information</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th>Name</th>
                        <td>{{ Auth::user()->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ Auth::user()->email }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Login History</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>Date/Time</th>
                            <th>IP Address</th>
                            
                            <th>Status</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $activeSessionIds = collect($sessions)->pluck('id')->toArray();
                        @endphp
                        @foreach($loginHistory as $login)
                        <tr @if(in_array($login->session_id, $activeSessionIds)) style="background:#e6ffe6;" @endif>
                            <td style="word-break:break-all;">{{ $login->session_id ?? 'N/A' }}</td>
                            <td>{{ $login->logged_in_at }}</td>
                            <td>{{ $login->ip_address }}</td>
                            
                            <td>{{ ucfirst($login->status) }}</td>
                            <td>
                                @if(in_array($login->session_id, $activeSessionIds))
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Active Sessions</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>IP Address</th>
                            <th>Last Activity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                        <tr>
                            <td style="word-break:break-all;">{{ $session->id }}</td>
                            <td>{{ $session->ip_address }}</td>
                            <td>{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->toDateTimeString() }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.terminateSession', $session->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Terminate</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop