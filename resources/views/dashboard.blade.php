@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="row">

    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <span class="float-right"><i class="fa fa-ticket fa-2x opacity-7"></i></span>
                    <h5 class="font-16 text-uppercase mb-0 text-white-50">Total Tickets</h5>
                </div>
                <h4 class="mb-0">{{ $stats['total'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-warning text-white">
            <div class="card-body">
                <div class="mb-4">
                    <span class="float-right"><i class="fa fa-folder-open fa-2x opacity-7"></i></span>
                    <h5 class="font-16 text-uppercase mb-0 text-white-50">Open</h5>
                </div>
                <h4 class="mb-0">{{ $stats['open'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-info text-white">
            <div class="card-body">
                <div class="mb-4">
                    <span class="float-right"><i class="fa fa-spinner fa-2x opacity-7"></i></span>
                    <h5 class="font-16 text-uppercase mb-0 text-white-50">In Progress</h5>
                </div>
                <h4 class="mb-0">{{ $stats['in_progress'] ?? 0 }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-success text-white">
            <div class="card-body">
                <div class="mb-4">
                    <span class="float-right"><i class="fa fa-check-circle fa-2x opacity-7"></i></span>
                    <h5 class="font-16 text-uppercase mb-0 text-white-50">Resolved</h5>
                </div>
                <h4 class="mb-0">{{ $stats['resolved'] }}</h4>
            </div>
        </div>
    </div>

    @if(auth()->user()->isStaffOrAdmin())
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-danger text-white">
            <div class="card-body">
                <div class="mb-4">
                    <span class="float-right"><i class="fa fa-exclamation-triangle fa-2x opacity-7"></i></span>
                    <h5 class="font-16 text-uppercase mb-0 text-white-50">SLA Breached</h5>
                </div>
                <h4 class="mb-0">{{ $stats['breached'] }}</h4>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-secondary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <span class="float-right"><i class="fa fa-user-times fa-2x opacity-7"></i></span>
                    <h5 class="font-16 text-uppercase mb-0 text-white-50">Unassigned</h5>
                </div>
                <h4 class="mb-0">{{ $stats['unassigned'] }}</h4>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Recent Tickets -->
<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Recent Tickets</h4>
                    <a href="{{ route('tickets.index') }}" class="btn btn-primary btn-sm waves-effect waves-light">View All</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Reference</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                @if(auth()->user()->isStaffOrAdmin())
                                    <th>Requester</th>
                                @endif
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $ticket)
                                <tr class="{{ $ticket->is_breached ? 'table-warning' : '' }}">
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="font-weight-bold">
                                            {{ $ticket->reference }}
                                        </a>
                                        @if($ticket->is_breached)
                                            <span class="badge badge-danger ml-1">SLA!</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($ticket->subject, 45) }}</td>
                                    <td>{{ $ticket->category->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->priority->badgeClass() }}">
                                            {{ $ticket->priority->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->statusBadgeClass() }}">
                                            {{ $ticket->statusLabel() }}
                                        </span>
                                    </td>
                                    @if(auth()->user()->isStaffOrAdmin())
                                        <td>{{ $ticket->requester->name }}</td>
                                    @endif
                                    <td class="text-muted">{{ $ticket->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No tickets yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
