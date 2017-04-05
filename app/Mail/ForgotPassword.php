<?php

namespace App\Mail;

use App\Entities\Api\V1\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $new_password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $new_password)
    {
        $this->user = $user;
        $this->new_password = $new_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Recover your account password')
            ->view('emails.forgot_password')
            ->with([
                'user' => $this->user,
                'new_password' => $this->new_password
            ]);
    }
}
