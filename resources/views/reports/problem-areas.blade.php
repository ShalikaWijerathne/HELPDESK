@extends('layouts.app')

@section('title', 'Problem Areas')
@section('page-title', 'Report: Recurring Problem Areas')

@section('content')

@include('reports._date-filter', ['route' => 'reports.problem-areas'])

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">Ticket Volume by Category ({{ $from }} to {{ $to }})</h4>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>Category</th><th>Tickets</th><th>Volume</th></tr>
                        </thead>
                        <tbody>
                            @php $max = $rows->max('ticket_count') ?: 1; @endphp
                            @forelse($rows as $row)
                                <tr>
                                    <td>{{ $row->name }}</td>
                                    <td><span class="badge badge-primary">{{ $row->ticket_count }}</span></td>
                                    <td style="width:40%">
                                        <div class="progress" style="height:14px">
                                            <div class="progress-bar bg-primary"
                                                 style="width:{{ round(($row->ticket_count / $max) * 100) }}%">
                                            </div>
                                        </div>
                                    </td>
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
