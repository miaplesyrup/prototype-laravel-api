<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $confirmationUrl;
    public $userId;
    public $confirmationToken;

    public function __construct($userId, $confirmationToken)
    {
        $this->userId = $userId;
        $this->confirmationToken = $confirmationToken;
        $this->confirmationUrl = config('app.url') . "/email/verify/{$userId}/{$confirmationToken}";
    }

    public function build()
    {
        return $this->view('emails.confirmation', [
            'userId' => $this->userId,
            'confirmationToken' => $this->confirmation_token,
            'confirmationUrl' => $this->confirmationUrl

        ]);
    }
}
