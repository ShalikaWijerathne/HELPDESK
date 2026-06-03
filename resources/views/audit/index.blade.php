@extends('layouts.app')

@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <form method="GET" class="form-inline mb-3">
                    <div class="form-group mr-2 mb-2">
                        <select name="user_id" class="form-control form-control-sm">
                            <option value="">All Users</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <input type="text" name="action" class="form-control form-control-sm"
                               placeholder="Action e.g. ticket.created" value="{{ request('action') }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm waves-effect mb-2 mr-1">Filter</button>
                    <a href="{{ route('audit.index') }}" class="btn btn-secondary btn-sm waves-effect mb-2">Clear</a>
                </form>
            </div>
        </div>

        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">Audit Log — {{ $logs->total() }} entries
                    <small class="text-muted font-weight-normal">(append-only)</small>
                </h4>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>When</th><th>Actor</th><th>Action</th><th>Record</th><th>Changes</th></tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="text-muted text-nowrap">{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $log->actorName() }}</td>
                                    <td><code>{{ $log->action }}</code></td>
                                    <td class="text-muted">{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                                    <td>
                                        @if($log->old_values)
                                            <span class="text-danger">{{ json_encode($log->old_values) }}</span> →
                                        @endif
                                        @if($log->new_values)
                                            <span class="text-success">{{ json_encode($log->new_values) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No log entries found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($logs->hasPages())
                    <div class="mt-3">{{ $logs->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
