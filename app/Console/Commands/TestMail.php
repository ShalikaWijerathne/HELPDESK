<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

class TestMail extends Command
{
    protected $signature   = 'mail:test {email}';
    protected $description = 'Send a test email via Mailtrap Sending API';

    public function handle(): int
    {
        $to = $this->argument('email');

        $email = (new MailtrapEmail())
            ->from(new Address('hello@demomailtrap.co', 'IT Help Desk'))
            ->to(new Address($to))
            ->subject('IT Help Desk — Test Email')
            ->html('<h2 style="color:#004a43">IT Help Desk</h2><p>✅ Email sending is working correctly!</p>');

        $response = MailtrapClient::initSendingEmails(
            apiKey: config('services.mailtrap.token')
        )->send($email);

        $this->info("Sent! Status: " . $response->getStatusCode());

        return self::SUCCESS;
    }
}
