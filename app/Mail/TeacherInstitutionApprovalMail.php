<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\TeacherRequest;

class TeacherInstitutionApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public TeacherRequest $teacherRequest;
    public string $confirmationUrl;

    public function __construct(TeacherRequest $teacherRequest, string $confirmationUrl)
    {
        $this->teacherRequest = $teacherRequest;
        $this->confirmationUrl = $confirmationUrl;
    }

    public function build(): static
    {
        return $this->subject('Validación de profesor en Klassify')
            ->view('emails.teacher-institution-approval');
    }
}
