@extends('layouts.app')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Ticket Categories</h4>
                    <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm waves-effect waves-light">
                        <i class="fa fa-plus"></i> New Category
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr><th>Name</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td class="font-weight-bold">{{ $category->name }}</td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="btn btn-primary btn-sm waves-effect waves-light mr-1">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        @if($category->is_active)
                                            <form method="POST" action="{{ route('categories.deactivate', $category) }}" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-warning btn-sm waves-effect"
                                                        onclick="return confirm('Deactivate this category?')">
                                                    <i class="fa fa-ban"></i> Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('categories.activate', $category) }}" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-success btn-sm waves-effect">
                                                    <i class="fa fa-check"></i> Activate
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">No categories yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($categories->hasPages())
                    <div class="mt-3">{{ $categories->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
