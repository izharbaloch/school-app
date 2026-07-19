<?php

namespace App\Mail;

use App\Models\SchoolSetting;
use App\Models\StudentFee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeeGeneratedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $schoolName;

    public function __construct(public StudentFee $studentFee)
    {
        $this->schoolName = SchoolSetting::get('school_name', config('app.name'));
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Fee Notice — ' . $this->schoolName);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.fee-generated');
    }

    public function attachments(): array
    {
        return [];
    }
}
