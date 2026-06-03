<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Attachment;
use App\Models\Category;
use App\Models\KbArticle;
use App\Models\Priority;
use App\Models\SlaPolicy;
use App\Models\Ticket;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user  = Auth::user();
        $query = Ticket::with(['requester', 'assignee', 'category', 'priority']);

        if ($user->isUser()) {
            $query->where('requester_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('priority_id')) {
            $query->where('priority_id', $request->priority_id);
        }

        if ($request->filled('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }

        // Sort breached tickets to the top, then by priority urgency
        $tickets = $query->orderByDesc('is_breached')
                         ->join('priorities', 'tickets.priority_id', '=', 'priorities.id')
                         ->orderByDesc('priorities.rank')
                         ->select('tickets.*')
                         ->paginate(20);

        $categories  = Category::active()->get();
        $priorities  = Priority::active()->get();
        $technicians = $user->isAdmin()
            ? User::whereHas('role', fn($q) => $q->whereIn('name', ['IT Staff', 'Admin']))->get()
            : collect();

        return view('tickets.index', compact('tickets', 'categories', 'priorities', 'technicians'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $priorities = Priority::active()->get();

        $users = Auth::user()->isStaffOrAdmin()
            ? User::whereHas('role', fn($q) => $q->where('name', 'User'))->orderBy('name')->get()
            : collect();

        return view('tickets.create', compact('categories', 'priorities', 'users'));
    }

    public function store(StoreTicketRequest $request)
    {
        $user = Auth::user();

        $requesterId = $user->isStaffOrAdmin() && $request->filled('requester_id')
            ? $request->requester_id
            : $user->id;

        $policy   = SlaPolicy::where('priority_id', $request->priority_id)->first();
        $slaDueAt = $policy ? now()->addMinutes($policy->resolution_minutes) : null;

        $ticket = Ticket::create([
            'reference'    => Ticket::generateReference(),
            'subject'      => $request->subject,
            'description'  => $request->description,
            'category_id'  => $request->category_id,
            'priority_id'  => $request->priority_id,
            'status'       => Ticket::STATUS_OPEN,
            'requester_id' => $requesterId,
            'logged_by_id' => ($requesterId !== $user->id) ? $user->id : null,
            'sla_due_at'   => $slaDueAt,
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');

            Attachment::create([
                'ticket_id'     => $ticket->id,
                'original_name' => $file->getClientOriginalName(),
                'stored_path'   => $path,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
            ]);
        }

        AuditLogger::log('ticket.created', $ticket, null, ['reference' => $ticket->reference]);
        NotificationService::ticketCreated($ticket);

        return redirect()->route('tickets.show', $ticket)
                         ->with('success', "Ticket {$ticket->reference} created successfully.");
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['requester', 'loggedBy', 'assignee', 'category',
                       'priority', 'attachments', 'kbArticle']);

        $user = Auth::user();

        $comments = $ticket->comments()
                           ->with('user')
                           ->when($user->isUser(), fn($q) => $q->where('is_internal', false))
                           ->get();

        $kbArticles  = $user->isStaffOrAdmin()
            ? KbArticle::published()->orderBy('title')->get()
            : collect();

        $technicians = $user->isStaffOrAdmin()
            ? User::whereHas('role', fn($q) => $q->whereIn('name', ['IT Staff', 'Admin']))->get()
            : collect();

        return view('tickets.show', compact('ticket', 'comments', 'kbArticles', 'technicians'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->authorize('changeStatus', $ticket);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', Ticket::STATUSES)],
        ]);

        $old = $ticket->status;
        $ticket->update(['status' => $request->status]);

        AuditLogger::log('ticket.status_changed', $ticket,
            ['status' => $old],
            ['status' => $ticket->status]
        );

        NotificationService::ticketUpdated($ticket, "Status changed to: {$ticket->statusLabel()}");

        return back()->with('success', 'Ticket status updated.');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorize('assign', $ticket);

        $request->validate([
            'assignee_id' => ['nullable', 'exists:users,id'],
        ]);

        $old = $ticket->assignee_id;
        $ticket->update(['assignee_id' => $request->assignee_id]);

        AuditLogger::log('ticket.assigned', $ticket,
            ['assignee_id' => $old],
            ['assignee_id' => $ticket->assignee_id]
        );

        NotificationService::ticketAssigned($ticket);

        return back()->with('success', 'Ticket assigned successfully.');
    }

    public function selfAssign(Ticket $ticket)
    {
        $this->authorize('assign', $ticket);

        $ticket->update(['assignee_id' => Auth::id()]);
        AuditLogger::log('ticket.assigned', $ticket, [], ['assignee_id' => Auth::id()]);

        return back()->with('success', 'Ticket assigned to you.');
    }

    public function reopen(Ticket $ticket)
    {
        $this->authorize('reopen', $ticket);

        $old = $ticket->status;
        $ticket->update(['status' => Ticket::STATUS_IN_PROGRESS]);

        AuditLogger::log('ticket.reopened', $ticket,
            ['status' => $old],
            ['status' => Ticket::STATUS_IN_PROGRESS]
        );

        NotificationService::ticketUpdated($ticket, 'Ticket was reopened.');

        return back()->with('success', 'Ticket reopened.');
    }

    public function addComment(Request $request, Ticket $ticket)
    {
        $this->authorize('addComment', $ticket);

        $request->validate([
            'body'        => ['required', 'string', 'max:5000'],
            'is_internal' => ['boolean'],
        ]);

        $user       = Auth::user();
        $isInternal = $user->isStaffOrAdmin() && $request->boolean('is_internal');

        $comment = $ticket->comments()->create([
            'user_id'     => $user->id,
            'body'        => $request->body,
            'is_internal' => $isInternal,
        ]);

        AuditLogger::log('ticket.commented', $ticket, [], [
            'comment_id'  => $comment->id,
            'is_internal' => $isInternal,
        ]);

        if (!$isInternal) {
            NotificationService::ticketCommented($ticket, $user);
        }

        return back()->with('success', 'Comment added.');
    }

    public function linkKbArticle(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $request->validate([
            'kb_article_id' => ['nullable', 'exists:kb_articles,id'],
        ]);

        $ticket->update(['kb_article_id' => $request->kb_article_id]);

        return back()->with('success', 'Knowledge base article linked.');
    }
}
