@extends('layouts.app')

@section('title', 'Technician Performance')
@section('page-title', 'Report: Technician Performance')

@section('content')

@include('reports._date-filter', ['route' => 'reports.technician-performance'])

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">Technician Performance ({{ $from }} to {{ $to }})</h4>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>Technician</th><th>Assigned</th><th>Resolved</th><th>SLA Breaches</th><th>Resolution Rate</th></tr>
                        </thead>
                        <tbody>
                            @forelse($technicians as $tech)
                                <tr>
                                    <td>{{ $tech['name'] }}</td>
                                    <td>{{ $tech['assigned'] }}</td>
                                    <td><span class="badge badge-success">{{ $tech['resolved'] }}</span></td>
                                    <td><span class="badge badge-danger">{{ $tech['breached'] }}</span></td>
                                    <td>
                                        @if($tech['assigned'] > 0)
                                            {{ round(($tech['resolved'] / $tech['assigned']) * 100) }}%
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No data for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
