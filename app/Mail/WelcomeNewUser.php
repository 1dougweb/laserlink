<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeNewUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $temporaryPassword
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $siteName = Setting::get('site_name', 'Laser Link');
        
        return new Envelope(
            subject: "Bem-vindo ao {$siteName}!",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-new-user',
            with: [
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'siteName' => Setting::get('site_name', 'Laser Link'),
                'loginUrl' => route('login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
