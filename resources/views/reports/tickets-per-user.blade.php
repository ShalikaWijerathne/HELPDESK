@extends('layouts.app')

@section('title', 'Tickets per User')
@section('page-title', 'Report: Tickets per User')

@section('content')

@include('reports._date-filter', ['route' => 'reports.tickets-per-user'])

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">Tickets Raised per User ({{ $from }} to {{ $to }})</h4>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>User</th><th>Email</th><th>Tickets Raised</th></tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td class="text-muted">{{ $user->email }}</td>
                                    <td><span class="badge badge-primary">{{ $user->ticket_count }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No data for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
