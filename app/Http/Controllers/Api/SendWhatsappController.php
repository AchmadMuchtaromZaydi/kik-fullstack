<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Console\Commands\SendWhatsAppMessage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SendWhatsappController extends Controller
{
    public function sendWhatsAppMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'required|numeric', 'digits_between:10,15',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all()
            ], 400);
        }

        try {
            $to = $request->to;
            $message = $request->message;
            // Dispatch the SendWhatsAppMessage command
            Artisan::call('whatsapp:send', [
                'to' => $to,
                'message' => $message,
            ]);

            $output = Artisan::output();

            if (strpos($output, "Failed") !== false) {
                // The command failed, handle the failure
                // Log, send notification, etc.
                return response()->json(['status' => 'error', 'message' => $output]);

            } else if (strpos($output, "false") !== false) {
                // device is stopped
                return response()->json(['status' => 'error', 'message' => $output]);
            }

            return response()->json(['status' => 'success', 'message' => 'WhatsApp message sent successfully']);
        } catch (\Exception $e) {
            // Handle exception, log error, etc.
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // public function sendWhatsAppMessage(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'to' => 'required|numeric', 'digits_between:10,15',
    //         'message' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             "status" => 'error',
    //             "message" => $validator->errors()->all()
    //         ], 400);
    //     }

    //     try {
    //         $to = $request->to;
    //         $message = $request->message;

    //         dispatch(new SendWhatsAppMessage($to, $message));

    //         return response()->json(['status' => 'queued', 'message' => 'WhatsApp message is queued for sending']);

    //     } catch (\Exception $e) {
    //         // Handle exception, log error, etc.
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    //     }
    // }
}
