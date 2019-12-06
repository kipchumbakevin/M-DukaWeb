<?php

namespace App\Http\Controllers;

use App\Code;
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
        $requestedusername = $request['username'];
        $user = User::where('id',Auth::user()->id)->first();
        $users = User::all();
        $usernames = [];
        foreach ($users as $one){
            array_push($usernames,$one->username);
        }
        if (in_array($requestedusername,$usernames) && $user->username!=$requestedusername){
            return response()->json([
                'message'=>"The username has already been taken",
            ]);
        }else {
            $user->update([
                'username' => $request['username'],
                'first_name' => $request['firstname'],
                'last_name' => $request['lastname'],
                'location' => $request['location']
            ]);
            return response()->json([
                'user' => $user,
            ]);
        }
    }

    public function checkNumberIfCorrect(Request $request)
    {
        $phonenumber =preg_replace("/^0/", "+254", $request->oldphone);
        $users = User::all();
        $newPhon = preg_replace("/^0/", "+254", $request->newphone);
        $phonNum = [];
        $userrs=User::where('id',Auth::user()->id)->first();
        foreach ($users as $user){
            array_push($phonNum,$user->phone);
        }
        if (in_array($phonenumber,$phonNum) && $userrs->phone==$phonenumber) {
            $thisuser = User::where('phone', $phonenumber)->first();
        } if (!in_array($phonenumber,$phonNum)){
        return response()->json([
            'message' => 'The old phone number does not exist',
        ]);
    }if (in_array($phonenumber,$phonNum) && $userrs->phone!=$phonenumber){
        return response()->json([
            'message' => 'The old phone number is not registered to you',
        ]);
    }
        if (in_array($newPhon,$phonNum)&&$userrs->phone==$newPhon){
            return response()->json([
                'message' => 'No change was detected',
            ]);
        }if (in_array($newPhon,$phonNum)&& $userrs->phone!=$newPhon){
        return response()->json([
            'message' => 'The phone number has already been taken',
        ]);
    }

		if(!Hash::check($request['pas'],$thisuser->password)){
			 return response()->json([
                'message' => 'Wrong password',
            ]);
		}
		else{
            return response()->json([
                'message' => 'Verification code will be sent',
            ],201);
        }


    }
    public function generateChangePhoneCode(Request $request)
    {
		$signature = $request->appSignature;
        $codetable = new Code();
        $codes = rand(100000,999999);
        $user = User::find(Auth::user()->id);
		$output = preg_replace("/^0/", "+254", $request->oldphone);
		$newnew = preg_replace("/^0/", "+254", $request->newphone);
        $phone = $user->phone;
        if ($phone==$output){
            $codetable->code=$codes;
            $codetable->phone=$output;
			$codetable->save();
            $username   = "mduka.com";
            $apiKey     = "04264f63d8b96a3880887e8e40499d6b05bde13cb2454ced59a369500a5a686e";
            $AT         = new AfricasTalking($username, $apiKey);
            $sms        = $AT->sms();
            $recipients = $newnew;
            $message    = "<#> Verification code:".$codes.": ".$signature;
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
        $code= $request->code;
        $codess = Code::where('code',$code)->first();
        $codees= $codess->code;
        $ph = $codess->phone;
        $user = User::find(Auth::user()->id);
		$output = preg_replace("/^0/", "+254", $request->newphone);
        $old = preg_replace("/^0/", "+254", $request->oldphone);
        if ($ph==$old && $codees==$code ) {
            $user->update([
                'phone' => $output
            ]);
            $codess->delete();
            return response()->json([
                'message' => 'success',
            ],201);
        }else{
                return response()->json([
                    'message' => 'Invalid code',
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
        ],201);
        } else {
            return response()->json([
                'message' => 'Invalid credentials',
            ]);
        }
    }

}
