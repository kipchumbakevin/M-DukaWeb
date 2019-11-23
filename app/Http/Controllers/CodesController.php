<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\Code;
use Exception;
use Illuminate\Http\Request;

class CodesController extends Controller
{
    public function signUpCode(Request $request)
    {
        $output = preg_replace("/^0/", "+254", $request->phone);
        $signupcode = rand(100000,999999);
        $codes = new Code();
        $codes->code=$signupcode;
        $username   = "mduka.com";
        $apiKey     = "04264f63d8b96a3880887e8e40499d6b05bde13cb2454ced59a369500a5a686e";
        $AT         = new AfricasTalking($username, $apiKey);
        $sms        = $AT->sms();
        $recipients = $output;
        $message    = "Verification code ".$signupcode;
        try {
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $recipients,
                'message' => $message,
            ]);
        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }
        $codes->save();
        return response()->json([
            'message' => 'Code sent',
        ],201);
    }
}
