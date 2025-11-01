<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailClass extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->data["code"])) {
            return $this->subject($this->data["subject"])
                ->view('emails.template-verification') // Blade template for the email
                ->with(['data' => $this->data]); // Pass data to the template
        } else {
            return $this->subject($this->data["subject"])
                ->view('emails.template-global') // Blade template for the email
                ->with(['data' => $this->data]); // Pass data to the template
        }
    }
}
