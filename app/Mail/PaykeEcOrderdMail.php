<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PaykeUser;

class PaykeEcOrderdMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $login_id;
    public string $login_password;
    public string $login_url;
    public string $order_id;

    /**
     * Create a new message instance.
     */
    public function __construct(PaykeUser $user, string $login_username, string $login_password, string $login_url)
    {
        $this->name = $user["user_name"];
        $this->login_id = $login_username;
        $this->login_password = $login_password;
        $this->login_url = $login_url;
        $this->order_id = $user["payke_order_id"];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PaykeECのご購入ありがとうございます。',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'email.payke_ec_ordered',
            with: [
                'name' => $this->name,
                'login_id' => $this->login_id,
                'login_password' => $this->login_password,
                'login_url' => $this->login_url,
                'order_id' => $this->order_id
            ]
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
