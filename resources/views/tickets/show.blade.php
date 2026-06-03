@extends('layouts.app')

@section('title', $ticket->reference)
@section('page-title', 'Ticket: ' . $ticket->reference)

@section('content')

<div class="row">

    <!-- Left: Ticket Details -->
    <div class="col-lg-8">

        <div class="card m-b-20">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="card-title mb-0">{{ $ticket->subject }}</h4>
                    <div>
                        <span class="badge badge-{{ $ticket->priority->badgeClass() }} mr-1">{{ $ticket->priority->name }}</span>
                        <span class="badge badge-{{ $ticket->statusBadgeClass() }}">{{ $ticket->statusLabel() }}</span>
                        @if($ticket->is_breached)
                            <span class="badge badge-danger ml-1"><i class="fa fa-exclamation-triangle"></i> SLA Breached</span>
                        @endif
                    </div>
                </div>
                <p class="text-muted" style="white-space:pre-wrap">{{ $ticket->description }}</p>
            </div>
        </div>

        <!-- Attachments -->
        @if($ticket->attachments->isNotEmpty())
            <div class="card m-b-20">
                <div class="card-body">
                    <h4 class="card-title"><i class="fa fa-paperclip"></i> Attachments</h4>
                    <ul class="list-group list-group-flush">
                        @foreach($ticket->attachments as $att)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span><i class="fa fa-file text-muted mr-2"></i>{{ $att->original_name }}</span>
                                <span class="d-flex align-items-center">
                                    <small class="text-muted mr-3">{{ $att->readableSize() }}</small>
                                    <a href="{{ $att->downloadUrl() }}" target="_blank" class="btn btn-primary btn-sm waves-effect waves-light">
                                        <i class="fa fa-download"></i>
                                    </a>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Comments -->
        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title"><i class="fa fa-comments"></i> Comments</h4>

                @forelse($comments as $comment)
                    <div class="media mb-3 p-3 {{ $comment->is_internal ? 'bg-warning-subtle border border-warning rounded' : 'bg-light rounded' }}">
                        <div class="media-body">
                            <div class="d-flex justify-content-between mb-1">
                                <strong>{{ $comment->user->name }}</strong>
                                <small class="text-muted">
                                    {{ $comment->created_at->format('d M Y H:i') }}
                                    @if($comment->is_internal)
                                        <span class="badge badge-warning ml-1">Internal Note</span>
                                    @endif
                                </small>
                            </div>
                            <p class="mb-0" style="white-space:pre-wrap">{{ $comment->body }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No comments yet.</p>
                @endforelse

                <form method="POST" action="{{ route('tickets.comments.store', $ticket) }}" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <textarea name="body" rows="4" class="form-control @error('body') is-invalid @enderror"
                                  placeholder="Write a comment...">{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary btn-sm waves-effect waves-light mr-3">
                            <i class="fa fa-paper-plane"></i> Post Comment
                        </button>
                        @if(auth()->user()->isStaffOrAdmin())
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="is_internal" id="isInternal" value="1">
                                <label class="custom-control-label" for="isInternal">Internal note (staff only)</label>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($ticket->kbArticle)
            <div class="alert alert-info">
                <i class="fa fa-book mr-1"></i>
                Resolved using KB article:
                <a href="{{ route('kb.show', $ticket->kbArticle) }}">{{ $ticket->kbArticle->title }}</a>
            </div>
        @endif

    </div>

    <!-- Right: Info & Actions -->
    <div class="col-lg-4">

        <div class="card m-b-20">
            <div class="card-body">
                <h4 class="card-title">Ticket Info</h4>
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted font-weight-normal">Reference</th><td><strong>{{ $ticket->reference }}</strong></td></tr>
                    <tr><th class="text-muted font-weight-normal">Category</th><td>{{ $ticket->category->name }}</td></tr>
                    <tr><th class="text-muted font-weight-normal">Requester</th><td>{{ $ticket->requester->name }}</td></tr>
                    @if($ticket->loggedBy)
                        <tr><th class="text-muted font-weight-normal">Logged by</th><td>{{ $ticket->loggedBy->name }}</td></tr>
                    @endif
                    <tr><th class="text-muted font-weight-normal">Assignee</th><td>{{ $ticket->assignee?->name ?? '— Unassigned —' }}</td></tr>
                    <tr><th class="text-muted font-weight-normal">Created</th><td>{{ $ticket->created_at->format('d M Y H:i') }}</td></tr>
                    @if($ticket->sla_due_at)
                        @php $mins = $ticket->slaMinutesRemaining(); @endphp
                        <tr>
                            <th class="text-muted font-weight-normal">SLA Due</th>
                            <td class="text-{{ $mins !== null && $mins < 0 ? 'danger' : ($mins < 60 ? 'warning' : 'success') }} font-weight-bold">
                                {{ $ticket->sla_due_at->format('d M Y H:i') }}
                                @if(!$ticket->isResolved())
                                    <br><small>{{ $mins < 0 ? abs($mins).' min overdue' : ($mins < 60 ? $mins.' min left' : round($mins/60,1).' h left') }}</small>
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        @if(auth()->user()->isStaffOrAdmin())

            <div class="card m-b-20">
                <div class="card-body">
                    <h4 class="card-title">Change Status</h4>
                    <form method="POST" action="{{ route('tickets.status', $ticket) }}">
                        @csrf @method('PATCH')
                        <div class="form-group">
                            <select name="status" class="form-control">
                                @foreach(\App\Models\Ticket::STATUSES as $s)
                                    <option value="{{ $s }}" {{ $ticket->status === $s ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $s)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-warning btn-sm waves-effect waves-light w-100">Update Status</button>
                    </form>
                </div>
            </div>

            <div class="card m-b-20">
                <div class="card-body">
                    <h4 class="card-title">Assign Ticket</h4>
                    <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                        @csrf @method('PATCH')
                        <div class="form-group">
                            <select name="assignee_id" class="form-control">
                                <option value="">— Unassigned —</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ $ticket->assignee_id == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-info btn-sm waves-effect waves-light w-100">Assign</button>
                    </form>
                    @if($ticket->assignee_id !== auth()->id())
                        <form method="POST" action="{{ route('tickets.self-assign', $ticket) }}" class="mt-2">
                            @csrf @method('PATCH')
                            <button class="btn btn-outline-info btn-sm waves-effect w-100">Assign to Me</button>
                        </form>
                    @endif
                </div>
            </div>

            @if($kbArticles->isNotEmpty())
                <div class="card m-b-20">
                    <div class="card-body">
                        <h4 class="card-title">Link KB Article</h4>
                        <form method="POST" action="{{ route('tickets.kb-article', $ticket) }}">
                            @csrf @method('PATCH')
                            <div class="form-group">
                                <select name="kb_article_id" class="form-control">
                                    <option value="">— None —</option>
                                    @foreach($kbArticles as $art)
                                        <option value="{{ $art->id }}" {{ $ticket->kb_article_id == $art->id ? 'selected' : '' }}>
                                            {{ Str::limit($art->title, 40) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-secondary btn-sm waves-effect waves-light w-100">Link Article</button>
                        </form>
                    </div>
                </div>
            @endif

        @endif

        @if(auth()->user()->isUser() && in_array($ticket->status, ['resolved', 'closed']))
            <div class="card m-b-20">
                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.reopen', $ticket) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-warning waves-effect w-100">
                            <i class="fa fa-redo"></i> Reopen Ticket
                        </button>
                    </form>
                </div>
            </div>
        @endif

    </div>

</div>

@endsection
