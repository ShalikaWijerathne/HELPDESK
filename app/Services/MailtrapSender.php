<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class MailtrapSender
{
    public static function send(User $recipient, Ticket $ticket, string $subject, string $message): void
    {
        $apiToken = config('services.mailtrap.token');

        if (!$apiToken) {
            logger()->warning('Mailtrap API token not configured — email skipped.');
            return;
        }

        try {
            $body = view('emails.ticket', [
                'ticket'       => $ticket,
                'emailMessage' => $message,
            ])->render();

            $email = (new MailtrapEmail())
                ->from(new Address(
                    env('MAIL_FROM_ADDRESS', 'hello@demomailtrap.co'),
                    env('MAIL_FROM_NAME', 'IT Help Desk')
                ))
                ->to(new Address($recipient->email, $recipient->name))
                ->subject($subject)
                ->html($body);

            MailtrapClient::initSendingEmails(apiKey: $apiToken)->send($email);

        } catch (\Exception $e) {
            logger()->error("Mailtrap send failed for {$recipient->email}: " . $e->getMessage());
        }
    }
}
