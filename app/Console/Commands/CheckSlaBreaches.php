<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Services\AuditLogger;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckSlaBreaches extends Command
{
    protected $signature   = 'tickets:check-sla';
    protected $description = 'Mark overdue tickets as breached and send escalation notifications.';

    public function handle(): int
    {
        $overdue = Ticket::whereNotNull('sla_due_at')
            ->where('sla_due_at', '<', now())
            ->where('is_breached', false)
            ->whereNotIn('status', [Ticket::STATUS_RESOLVED, Ticket::STATUS_CLOSED])
            ->with(['assignee', 'requester'])
            ->get();

        if ($overdue->isEmpty()) {
            $this->info('No new SLA breaches found.');
            return self::SUCCESS;
        }

        foreach ($overdue as $ticket) {
            $ticket->update(['is_breached' => true]);

            AuditLogger::log('ticket.sla_breached', $ticket,
                ['is_breached' => false],
                ['is_breached' => true]
            );

            NotificationService::slaBreach($ticket);

            $this->warn("  BREACH: {$ticket->reference} — due at {$ticket->sla_due_at}");
        }

        $this->info("{$overdue->count()} ticket(s) marked as breached.");

        return self::SUCCESS;
    }
}
