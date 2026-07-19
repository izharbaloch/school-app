<?php

namespace App\Mail;

use App\Models\Admission;
use App\Models\SchoolSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdmissionStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $schoolName;

    public function __construct(public Admission $admission)
    {
        $this->schoolName = SchoolSetting::get('school_name', config('app.name'));
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->admission->status) {
            Admission::STATUS_ACCEPTED => 'Admission Application Accepted — ' . $this->schoolName,
            Admission::STATUS_REJECTED => 'Admission Application Update — ' . $this->schoolName,
            Admission::STATUS_ENROLLED => 'Enrollment Confirmed — ' . $this->schoolName,
            default                    => 'Admission Status Update — ' . $this->schoolName,
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admission-status');
    }

    public function attachments(): array
    {
        return [];
    }
}
