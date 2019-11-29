<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Cast\Double;
use AfricasTalking\SDK\AfricasTalking;
use phpseclib\Crypt\Random;

class ChangePersonalInfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function changedetails(Request $request){
        $user = User::find(Auth::user()->id);
        $user->update([
            'username'=>$request['username'],
            'first_name'=>$request['firstname'],
            'last_name'=>$request['lastname'],
			'location'=>$request['location']
        ]);
        return response()->json([
			     'user'=>$user,
        ]);
    }

    public function generateChangePhoneCode(Request $request)
    {
        $codes = rand(1000,9999);
        $user = User::find(Auth::user()->id);
		$output = preg_replace("/^0/", "+254", $request->oldphone);
        $phone = $user->phone;
        if ( Hash::check($request['passcode'],$user->password) && $phone==$output){
            $user->update([
                'code'=>$codes
            ]);
            $username   = "mduka.com";
            $apiKey     = "04264f63d8b96a3880887e8e40499d6b05bde13cb2454ced59a369500a5a686e";
            $AT         = new AfricasTalking($username, $apiKey);
            $sms        = $AT->sms();
            $recipients = $output;
            $message    = "Verification code ".$codes;
            try {
                // Thats it, hit send and we'll take care of the rest
                $result = $sms->send([
                    'to'      => $recipients,
                    'message' => $message,
                ]);
            } catch (Exception $e) {
                echo "Error: ".$e->getMessage();
            }
			 return response()->json([
                'message' => 'success',
            ],201);
        }else{
            return response()->json([
                'message' => 'Numbers do not match',
            ]);
        }
    }

    public function changePhone(Request $request)
    {
      //  Hash::check($request['pass'],$user->password)
        $user = User::find(Auth::user()->id);
		$output = preg_replace("/^0/", "+254", $request->newphone);
        $code= $user->code;
        if ($code==$request['code']) {
            $user->update([
                'phone' => $output,
				'code'=>null
            ]);
            return response()->json([
                'message' => 'success',
            ],201);
        }else{
            return response()->json([
                'message' => 'Invalid credentials',
            ]);
        }
    }

    public function changePassword(Request $request)
    {
        $user = User::find(Auth::user()->id);
		$tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        if (Hash::check($request['oldpass'],$user->password)) {
            $user->update([
                'password' =>  Hash::make($request['newpass'])
            ]);
            return response()->json([
            'access_token' => $tokenResult->accessToken,
            'user' => $user,
            'message'=>"Password changed successfuly",
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
        } else {
            return response()->json([
                'message' => 'Invalid credentials',
            ]);
        }
    }

}
