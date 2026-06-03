@extends('layouts.app')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Notifications</h4>
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-secondary btn-sm waves-effect">
                            <i class="fa fa-check-double"></i> Mark All Read
                        </button>
                    </form>
                </div>

                <ul class="list-group list-group-flush">
                    @forelse($notifications as $notif)
                        <li class="list-group-item {{ $notif->isUnread() ? 'bg-light' : '' }} px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    @if($notif->isUnread())
                                        <span class="badge badge-primary mr-2">New</span>
                                    @endif
                                    {{ $notif->message }}
                                    @if($notif->ticket)
                                        &mdash;
                                        <a href="{{ route('tickets.show', $notif->ticket) }}">
                                            {{ $notif->ticket->reference }}
                                        </a>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center ml-3 flex-shrink-0">
                                    <small class="text-muted mr-2">{{ $notif->created_at->diffForHumans() }}</small>
                                    @if($notif->isUnread())
                                        <form method="POST" action="{{ route('notifications.read', $notif) }}">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-secondary" title="Mark as read">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-5 px-0">
                            <i class="fa fa-bell-slash fa-2x mb-2 d-block"></i>
                            No notifications.
                        </li>
                    @endforelse
                </ul>

                @if($notifications->hasPages())
                    <div class="mt-3">{{ $notifications->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
