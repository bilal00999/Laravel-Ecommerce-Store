@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 10px; margin-bottom: 2rem;">
    <h1 style="margin: 0; font-size: 2rem; font-weight: 700;">
        <i class="bi bi-people"></i> Manage Users
    </h1>
    <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">View and manage user accounts</p>
</div>

<div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow-x: auto;">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach(\App\Models\User::orderBy('created_at', 'desc')->get() as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span style="background: {{ $user->is_admin ? '#4caf50' : '#2196f3' }}; color: white; padding: 0.3rem 0.6rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                            {{ $user->is_admin ? 'Admin' : 'User' }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary" disabled>View</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem;">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>
@endsection
