@extends('layouts.app')

@section('title', 'Tickets')
@section('page-title', 'Tickets')

@section('content')

<!-- Filter -->
<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-2 mb-2">
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All Statuses</option>
                            @foreach(\App\Models\Ticket::STATUSES as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $s)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select name="category_id" class="form-control form-control-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select name="priority_id" class="form-control form-control-sm">
                            <option value="">All Priorities</option>
                            @foreach($priorities as $pri)
                                <option value="{{ $pri->id }}" {{ request('priority_id') == $pri->id ? 'selected' : '' }}>
                                    {{ $pri->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light mb-2 mr-1">
                        <i class="fa fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm waves-effect mb-2">Clear</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Table -->
<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Tickets ({{ $tickets->total() }})</h4>
                    <a href="{{ route('tickets.create') }}" class="btn btn-success btn-sm waves-effect waves-light">
                        <i class="fa fa-plus"></i> New Ticket
                    </a>
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
                                    <th>Assignee</th>
                                @endif
                                <th>SLA</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr class="{{ $ticket->is_breached ? 'table-warning' : '' }}">
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket) }}" class="font-weight-bold">
                                            {{ $ticket->reference }}
                                        </a>
                                        @if($ticket->is_breached)
                                            <span class="badge badge-danger ml-1">SLA!</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($ticket->subject, 40) }}</td>
                                    <td><span class="badge badge-secondary">{{ $ticket->category->name }}</span></td>
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
                                        <td>{{ $ticket->assignee?->name ?? '—' }}</td>
                                    @endif
                                    <td>
                                        @if($ticket->sla_due_at && !$ticket->isResolved())
                                            @php $mins = $ticket->slaMinutesRemaining(); @endphp
                                            <span class="text-{{ $mins < 0 ? 'danger' : ($mins < 60 ? 'warning' : 'success') }} font-weight-bold">
                                                {{ $mins < 0 ? 'Overdue' : ($mins < 60 ? $mins.'m' : round($mins/60).'h') }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $ticket->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">No tickets found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tickets->hasPages())
                    <div class="mt-3">{{ $tickets->withQueryString()->links() }}</div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
