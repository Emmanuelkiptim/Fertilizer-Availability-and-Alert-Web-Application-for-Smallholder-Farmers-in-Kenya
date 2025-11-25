@extends('adminlte::page')

@section('title', 'Users Management')

@section('content_header')
    <h1>Users Management</h1>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">All Users</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allUsers as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    @if(
                                        // Only original admin can delete other admins
                                        (auth()->user()->role === 'admin' && (auth()->user()->id === 1 || auth()->user()->email === 'admin@example.com'))
                                            ? ($user->role !== 'admin' || ($user->role === 'admin' && $user->id !== 1 && $user->email !== 'admin@example.com'))
                                            : ($user->role !== 'admin')
                                    )
        
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Farmers</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($farmers as $farmer)
                            <tr>
                                <td>{{ $farmer->id }}</td>
                                <td>{{ $farmer->name }}</td>
                                <td>{{ $farmer->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Agrovets</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Shop Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agrovets as $agrovet)
                            <tr>
                                <td>{{ $agrovet->user ? $agrovet->user->id : $agrovet->user_id }}</td>
                                <td>{{ $agrovet->user ? $agrovet->user->name : '' }}</td>
                                <td>{{ $agrovet->shopname }}</td>
                                <td>{{ $agrovet->user ? $agrovet->user->email : '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">Admin</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->id }}</td>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <!-- Add New Admin Form -->
        <div class="card mt-5">
            <div class="card-header bg-primary text-white">Add New Admin</div>
            <div class="card-body">
                <form action="{{ route('users.addAdmin') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Add Admin</button>
                </form>
            </div>
        </div>
@section('css')
    <style>
        @media (max-width: 768px) {
            .card {
                margin-bottom: 1rem;
            }
            .table-responsive {
                overflow-x: auto;
            }
            .table th, .table td {
                white-space: nowrap;
            }
            .btn {
                font-size: 0.9rem;
                padding: 0.4rem 0.7rem;
            }
        }
    </style>
@endsection
@endsection
