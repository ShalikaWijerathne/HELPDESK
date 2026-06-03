<?php

namespace App\Services;

use App\Mail\TicketMail;
use App\Models\Notification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public static function ticketCreated(Ticket $ticket): void
    {
        $message = "Your ticket {$ticket->reference} has been created successfully.";
        self::notify($ticket->requester, $ticket, 'ticket_created', $message);
        self::email($ticket->requester, $ticket, "Ticket Created: {$ticket->reference}", $message);
    }

    public static function ticketAssigned(Ticket $ticket): void
    {
        $ticket->refresh();

        $userMessage = "Ticket {$ticket->reference} has been assigned to a technician.";
        self::notify($ticket->requester, $ticket, 'ticket_assigned', $userMessage);
        self::email($ticket->requester, $ticket, "Ticket Assigned: {$ticket->reference}", $userMessage);

        if ($ticket->assignee && $ticket->assignee_id !== $ticket->requester_id) {
            $staffMessage = "Ticket {$ticket->reference} has been assigned to you.";
            self::notify($ticket->assignee, $ticket, 'ticket_assigned', $staffMessage);
            self::email($ticket->assignee, $ticket, "Ticket Assigned to You: {$ticket->reference}", $staffMessage);
        }
    }

    public static function ticketUpdated(Ticket $ticket, string $message): void
    {
        $recipients = collect([$ticket->requester, $ticket->assignee])
            ->filter()
            ->unique('id');

        foreach ($recipients as $recipient) {
            self::notify($recipient, $ticket, 'ticket_updated', $message);
            self::email($recipient, $ticket, "Ticket Update: {$ticket->reference}", $message);
        }
    }

    public static function ticketCommented(Ticket $ticket, User $commenter): void
    {
        $message    = "A new comment was added to ticket {$ticket->reference}.";
        $recipients = collect([$ticket->requester, $ticket->assignee])
            ->filter()
            ->unique('id')
            ->reject(fn($u) => $u->id === $commenter->id);

        foreach ($recipients as $recipient) {
            self::notify($recipient, $ticket, 'ticket_commented', $message);
            self::email($recipient, $ticket, "New Comment on Ticket: {$ticket->reference}", $message);
        }
    }

    public static function slaBreach(Ticket $ticket): void
    {
        $message = "SLA BREACH: Ticket {$ticket->reference} has exceeded its SLA deadline.";

        if ($ticket->assignee) {
            self::notify($ticket->assignee, $ticket, 'sla_breached', $message);
            self::email($ticket->assignee, $ticket, "SLA Breach Alert: {$ticket->reference}", $message);
        }

        User::whereHas('role', fn($q) => $q->where('name', 'Admin'))->each(function ($admin) use ($ticket, $message) {
            self::notify($admin, $ticket, 'sla_breached', $message);
            self::email($admin, $ticket, "SLA Breach Alert: {$ticket->reference}", $message);
        });
    }

    private static function notify(User $user, Ticket $ticket, string $type, string $message): void
    {
        Notification::create([
            'user_id'   => $user->id,
            'ticket_id' => $ticket->id,
            'type'      => $type,
            'message'   => $message,
        ]);
    }

    private static function email(User $user, Ticket $ticket, string $subject, string $message): void
    {
        try {
            Mail::to($user->email, $user->name)
                ->send(new TicketMail($ticket, $subject, $message));
        } catch (\Exception $e) {
            logger()->error("Mail failed for {$user->email}: " . $e->getMessage());
        }
    }
}
