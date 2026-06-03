@extends('layouts.app')

@section('title', $article->title)
@section('page-title', 'Knowledge Base')

@section('content')

<div class="row">
    <div class="col-lg-9 offset-lg-1">
        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="card-title mb-1">{{ $article->title }}</h4>
                        <small class="text-muted">
                            {{ $article->category?->name ?? 'Uncategorised' }}
                            &middot; by {{ $article->author->name }}
                            &middot; {{ $article->updated_at->format('d M Y') }}
                            @if(!$article->is_published)
                                <span class="badge badge-warning ml-1">Draft</span>
                            @endif
                        </small>
                    </div>
                    @if(auth()->user()->isStaffOrAdmin())
                        <a href="{{ route('kb.edit', $article) }}" class="btn btn-primary btn-sm waves-effect waves-light">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    @endif
                </div>

                <hr>
                <div style="white-space:pre-wrap; line-height:1.8">{{ $article->body }}</div>

                <hr>
                <a href="{{ route('kb.index') }}" class="btn btn-secondary btn-sm waves-effect">
                    <i class="fa fa-arrow-left"></i> Back to Knowledge Base
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
