<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationVoucherMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $user, $voucher)
    {
        $this->subject = $subject;
        $this->user = $user;
        $this->voucher = $voucher;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->with([
                'user' => $this->user,
                'voucher' => $this->voucher,
            ])
            ->markdown('emails.registration-voucher');
    }
}
