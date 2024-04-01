<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PaykeUser;

class ErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;
    public string $message_text;
    public string $jsondata;
    public string $logs;

    /**
     * Create a new message instance.
     */
    public function __construct(string $title, string $message_text, $jsondata = [], array $errorlogs = [])
    {
        $this->title = $title;
        $this->message_text = $message_text;
        $this->jsondata = $jsondata;
        $this->logs = implode("<br />", $errorlogs);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【PaykeMAエラー】".$this->title
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $message_html = str_replace("\n", "<br />", $this->message_text);
        $jsondata_html = str_replace([" ", "\\n", '\\"'], ["&nbsp;&nbsp;", "<br />", '"'],json_encode($this->jsondata, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        return new Content(
            view: 'email.error',
            with: [
                'message_html' => $message_html,
                'jsondata_html' => $jsondata_html
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
