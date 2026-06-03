@extends('layouts.app')

@section('title', 'Priorities')
@section('page-title', 'Priorities')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Ticket Priorities</h4>
                    <a href="{{ route('priorities.create') }}" class="btn btn-success btn-sm waves-effect waves-light">
                        <i class="fa fa-plus"></i> New Priority
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>Name</th><th>Rank</th><th>SLA Response</th><th>SLA Resolution</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            @forelse($priorities as $priority)
                                <tr>
                                    <td><span class="badge badge-{{ $priority->badgeClass() }}">{{ $priority->name }}</span></td>
                                    <td>{{ $priority->rank }}</td>
                                    <td>{{ $priority->slaPolicy ? $priority->slaPolicy->response_minutes.' min' : '—' }}</td>
                                    <td>{{ $priority->slaPolicy ? $priority->slaPolicy->resolution_minutes.' min' : '—' }}</td>
                                    <td>
                                        <a href="{{ route('priorities.edit', $priority) }}"
                                           class="btn btn-primary btn-sm waves-effect waves-light">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No priorities yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
