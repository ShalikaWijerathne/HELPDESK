<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        // Only admins can view reports
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Tickets per user report — how many tickets each user has raised.
     */
    public function ticketsPerUser(Request $request)
    {
        $from = $request->input('from', now()->subMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        $rows = User::withCount(['ticketsRaised as ticket_count' => function ($q) use ($from, $to) {
                      $q->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
                  }])
                  ->orderByDesc('ticket_count')
                  ->get();

        return view('reports.tickets-per-user', compact('rows', 'from', 'to'));
    }

    /**
     * Technician performance report — assigned, resolved, breached per technician.
     */
    public function technicianPerformance(Request $request)
    {
        $from = $request->input('from', now()->subMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        $technicians = User::whereHas('role', fn($q) => $q->whereIn('name', ['IT Staff', 'Admin']))
            ->with(['ticketsAssigned' => function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
            }])
            ->get()
            ->map(function ($tech) {
                $assigned = $tech->ticketsAssigned;
                return [
                    'name'     => $tech->name,
                    'assigned' => $assigned->count(),
                    'resolved' => $assigned->whereIn('status', ['resolved', 'closed'])->count(),
                    'breached' => $assigned->where('is_breached', true)->count(),
                ];
            });

        return view('reports.technician-performance', compact('technicians', 'from', 'to'));
    }

    /**
     * Problem areas report — ticket volume by category.
     */
    public function problemAreas(Request $request)
    {
        $from = $request->input('from', now()->subMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        $rows = Category::withCount(['tickets as ticket_count' => function ($q) use ($from, $to) {
                     $q->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
                 }])
                 ->orderByDesc('ticket_count')
                 ->get();

        return view('reports.problem-areas', compact('rows', 'from', 'to'));
    }
}
