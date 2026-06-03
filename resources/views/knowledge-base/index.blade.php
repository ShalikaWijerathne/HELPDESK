@extends('layouts.app')

@section('title', 'Knowledge Base')
@section('page-title', 'Knowledge Base')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card m-b-20">
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-2 mb-2">
                        <input type="text" name="search" class="form-control form-control-sm"
                               placeholder="Search articles..." value="{{ request('search') }}">
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <select name="category_id" class="form-control form-control-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm waves-effect mb-2 mr-1">
                        <i class="fa fa-search"></i> Search
                    </button>
                    <a href="{{ route('kb.index') }}" class="btn btn-secondary btn-sm waves-effect mb-2">Clear</a>
                </form>
            </div>
        </div>

        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Articles ({{ $articles->total() }})</h4>
                    @if(auth()->user()->isStaffOrAdmin())
                        <a href="{{ route('kb.create') }}" class="btn btn-success btn-sm waves-effect waves-light">
                            <i class="fa fa-plus"></i> New Article
                        </a>
                    @endif
                </div>

                @forelse($articles as $article)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">
                                    <a href="{{ route('kb.show', $article) }}" class="text-dark">{{ $article->title }}</a>
                                </h5>
                                <small class="text-muted">
                                    {{ $article->category?->name ?? 'Uncategorised' }}
                                    &middot; by {{ $article->author->name }}
                                    &middot; {{ $article->updated_at->format('d M Y') }}
                                </small>
                            </div>
                            <div class="d-flex align-items-center ml-2">
                                @if(!$article->is_published)
                                    <span class="badge badge-warning mr-2">Draft</span>
                                @endif
                                @if(auth()->user()->isStaffOrAdmin())
                                    <a href="{{ route('kb.edit', $article) }}" class="btn btn-sm btn-outline-primary waves-effect">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="fa fa-search fa-2x mb-2 d-block"></i>
                        No articles found.
                    </div>
                @endforelse

                @if($articles->hasPages())
                    <div class="mt-3">{{ $articles->withQueryString()->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
