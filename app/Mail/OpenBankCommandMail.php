<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OpenBankCommandMail extends Mailable
{
    use Queueable, SerializesModels;

    public $banks;

    /**
     * Create a new message instance.
     *
     * @param $banks
     */
    public function __construct($banks)
    {
        //
        $this->banks = $banks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.command.open_bank', ['banks' => $this->banks]);
    }
}
