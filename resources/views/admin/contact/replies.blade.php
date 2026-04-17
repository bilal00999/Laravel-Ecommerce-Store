@extends('admin.layout')

@section('title', 'Contact Replies')
@section('page-title', 'Contact Message Replies')

@section('content')
<div class="admin-content">
    <!-- Filter Tabs -->
    <div style="background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin-bottom: 2rem; padding: 1.5rem;">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('admin.contact.replies') }}" 
               class="btn btn-sm @if(!request('status')) btn-primary @else btn-outline-primary @endif">
                <i class="bi bi-list-ul"></i> All ({{ $counts['all'] ?? 0 }})
            </a>
            <a href="{{ route('admin.contact.replies', ['status' => 'pending']) }}"
               class="btn btn-sm @if(request('status') === 'pending') btn-warning @else btn-outline-warning @endif">
                <i class="bi bi-clock-history"></i> Pending ({{ $counts['pending'] ?? 0 }})
            </a>
            <a href="{{ route('admin.contact.replies', ['status' => 'read']) }}"
               class="btn btn-sm @if(request('status') === 'read') btn-info @else btn-outline-info @endif">
                <i class="bi bi-check"></i> Read ({{ $counts['read'] ?? 0 }})
            </a>
            <a href="{{ route('admin.contact.replies', ['status' => 'replied']) }}"
               class="btn btn-sm @if(request('status') === 'replied') btn-success @else btn-outline-success @endif">
                <i class="bi bi-check2-all"></i> Replied ({{ $counts['replied'] ?? 0 }})
            </a>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Message Preview</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td>
                            <strong>{{ $message->user->name }}</strong><br>
                            <small style="color: #999;">{{ $message->email }}</small>
                        </td>
                        <td>
                            <strong>{{ $message->subject }}</strong>
                        </td>
                        <td>
                            <small style="color: #666;">{{ Str::limit($message->message, 50, '...') }}</small>
                        </td>
                        <td>
                            <small style="color: #999;">{{ $message->created_at->format('M d, Y') }}<br>{{ $message->created_at->format('g:i A') }}</small>
                        </td>
                        <td>
                            <span class="badge-status badge-{{ $message->status }}">
                                {{ ucfirst($message->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.contact.show', $message) }}" class="btn-action btn-view">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #999;">
                            <i class="bi bi-inbox" style="font-size: 2.5rem; opacity: 0.5;"></i>
                            <p style="margin-top: 1rem; font-size: 1.1rem;">No messages found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $messages->appends(request()->query())->links() }}
    </div>
</div>

<style>
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }
    .btn-primary {
        background: #667eea;
        color: white;
    }
    .btn-primary:hover {
        background: #5568d3;
    }
    .btn-outline-primary {
        background: white;
        color: #667eea;
        border-color: #667eea;
    }
    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
    }
    .btn-warning {
        background: #ffc107;
        color: #000;
    }
    .btn-outline-warning {
        background: white;
        color: #ffc107;
        border-color: #ffc107;
    }
    .btn-info {
        background: #17a2b8;
        color: white;
    }
    .btn-outline-info {
        background: white;
        color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-success {
        background: #28a745;
        color: white;
    }
    .btn-outline-success {
        background: white;
        color: #28a745;
        border-color: #28a745;
    }
    .btn-view {
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 6px;
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .btn-view:hover {
        background: #1976d2;
        color: white;
    }
</style>
@endsection
