<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWhatsAppMessage extends Command implements ShouldQueue
{
    protected $signature = 'whatsapp:send {to} {message} {--tries=3}';
    protected $description = 'Send a WhatsApp message';

    public function handle()
    {
        $to = $this->argument('to');
        $message = $this->argument('message');

        $nomor_valid = "";

        if (substr($to, 0, 2) === "08") {
            // echo "The string starts with '08'.";
            $oldString = "08";
            $newString = "628";
            $nomor_valid = preg_replace('/' . $oldString . '/', $newString, $to, 1);
        } else {
            $this->error("Failed to send WhatsApp. No Whatsapp invalid");
        }

        try {

            $body = array(
                "api_key" => "4339af6b970edd4010d4fb1aa094207ebccb9e16",
                "receiver" => $nomor_valid,
                "data" => array("message" => $message)
            );

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://whatsapp.kesenianbanyuwangi.com/api/send-message",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($body),
                CURLOPT_HTTPHEADER => [
                    "Accept: */*",
                    "Content-Type: application/json",
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $this->error("Failed to send WhatsApp message to {$to}. Error: {$err}");
            } else {
                $this->info("WhatsApp message sent to {$to}. {$response}");
            }
        } catch (\Exception $e) {
            $this->error("Failed to send WhatsApp message to {$to}");
        }
    }
}
