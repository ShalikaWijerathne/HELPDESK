@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">System Users</h4>
                    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm waves-effect waves-light">
                        <i class="fa fa-user-plus"></i> New User
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tickets Raised</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="font-weight-bold">{{ $user->name }}</td>
                                    <td class="text-muted">{{ $user->email }}</td>
                                    <td>
                                        <span class="badge badge-{{ $user->isAdmin() ? 'danger' : ($user->isStaff() ? 'warning' : 'primary') }}">
                                            {{ $user->role?->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->ticketsRaised()->count() }}</td>
                                    <td class="text-muted">{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="btn btn-primary btn-sm waves-effect waves-light">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="mt-3">{{ $users->links() }}</div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
