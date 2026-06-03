<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

/**
 * Defines who can do what with a Ticket.
 * Called automatically by Laravel when you use $this->authorize() in controllers.
 */
class TicketPolicy
{
    // Anyone logged in can create a ticket
    public function create(User $user): bool
    {
        return true;
    }

    // Users can only view their own tickets; staff and admin can view any
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->isStaffOrAdmin() || $ticket->requester_id === $user->id;
    }

    // Only staff and admin can update ticket details (subject, description, etc.)
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->isStaffOrAdmin();
    }

    // Only staff and admin can change ticket status
    public function changeStatus(User $user, Ticket $ticket): bool
    {
        return $user->isStaffOrAdmin();
    }

    // Only staff and admin can assign tickets
    public function assign(User $user, Ticket $ticket): bool
    {
        return $user->isStaffOrAdmin();
    }

    // Users can reopen their own resolved/closed ticket; staff/admin can reopen any
    public function reopen(User $user, Ticket $ticket): bool
    {
        return $user->isStaffOrAdmin() || $ticket->requester_id === $user->id;
    }

    // Anyone who can view the ticket can comment
    public function addComment(User $user, Ticket $ticket): bool
    {
        return $this->view($user, $ticket);
    }
}
