<?php

namespace App\Jobs;

use App\Mail\SendMailClass;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $emailData;

    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    public function handle()
    {
        // Logic to send emails using $this->emailData
        // Example: Mail::to($this->emailData['recipient'])->send(new YourMailClass($this->emailData));
        // var_dump($this->emailData->data);
        // die;
        Mail::to($this->emailData->data['recipient'])->send(new SendMailClass($this->emailData->data));
    }
}
