<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->isStaffOrAdmin()) {
            $stats = [
                'total'       => Ticket::count(),
                'open'        => Ticket::where('status', Ticket::STATUS_OPEN)->count(),
                'in_progress' => Ticket::where('status', Ticket::STATUS_IN_PROGRESS)->count(),
                'on_hold'     => Ticket::where('status', Ticket::STATUS_ON_HOLD)->count(),
                'resolved'    => Ticket::where('status', Ticket::STATUS_RESOLVED)->count(),
                'closed'      => Ticket::where('status', Ticket::STATUS_CLOSED)->count(),
                'breached'    => Ticket::where('is_breached', true)
                                       ->whereNotIn('status', [Ticket::STATUS_RESOLVED, Ticket::STATUS_CLOSED])
                                       ->count(),
                'unassigned'  => Ticket::whereNull('assignee_id')
                                       ->whereNotIn('status', [Ticket::STATUS_RESOLVED, Ticket::STATUS_CLOSED])
                                       ->count(),
            ];

            $recentTickets = Ticket::with(['requester', 'priority', 'category'])
                                   ->latest()
                                   ->take(10)
                                   ->get();
        } else {
            $myTickets = $user->ticketsRaised();

            $stats = [
                'total'       => $myTickets->count(),
                'open'        => $myTickets->clone()->where('status', Ticket::STATUS_OPEN)->count(),
                'in_progress' => $myTickets->clone()->where('status', Ticket::STATUS_IN_PROGRESS)->count(),
                'resolved'    => $myTickets->clone()->where('status', Ticket::STATUS_RESOLVED)->count(),
                'closed'      => $myTickets->clone()->where('status', Ticket::STATUS_CLOSED)->count(),
            ];

            $recentTickets = $myTickets->clone()
                                       ->with(['priority', 'category'])
                                       ->latest()
                                       ->take(10)
                                       ->get();
        }

        return view('dashboard', compact('stats', 'recentTickets'));
    }
}
