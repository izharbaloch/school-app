<?php

namespace App\Mail;

use App\Models\LeaveApplication;
use App\Models\SchoolSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $schoolName;

    public function __construct(public LeaveApplication $application)
    {
        $this->schoolName = SchoolSetting::get('school_name', config('app.name'));
    }

    public function envelope(): Envelope
    {
        $label = $this->application->status === LeaveApplication::STATUS_APPROVED
            ? 'Approved'
            : 'Not Approved';

        return new Envelope(
            subject: "Leave Application {$label} — {$this->schoolName}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.leave-status');
    }

    public function attachments(): array
    {
        return [];
    }
}
