<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserAccountEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $type;
    public $notification;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $type, $notification = null)
    {
        $this->user = $user;
        // Set the type of email to be sent
        // verify_ok: Gửi mail khi admin phê duyệt tài khoản
        // banned: Gửi mail khi admin khóa tài khoản
        // unbanned: Gửi mail khi admin mở khóa tài khoản
        $this->type = $type;
        $this->notification = $notification;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->type == 'verify_ok') {
            return new Envelope(
                subject: 'Phê duyệt tài khoản',
            );
        } elseif ($this->type == 'banned') {
            return new Envelope(
                subject: 'Khóa tài khoản',
            );
        } elseif ($this->type == 'unbanned') {
            return new Envelope(
                subject: 'Mở khóa tài khoản',
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->type == 'verify_ok') {
            return new Content(
                view: 'emails.verified_successfully',
            );
        } elseif ($this->type == 'banned') {
            return new Content(
                view: 'emails.banned',
            );
        } elseif ($this->type == 'unbanned') {
            return new Content(
                view: 'emails.unbanned',
            );
        }
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
