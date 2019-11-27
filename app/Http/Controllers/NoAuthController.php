<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\AllTypes;
use App\Category;
use App\ItemGroup;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NoAuthController extends Controller
{
    public function getCategories(){
        $categories = Category::all();
        return $categories;
    }
    public function getGroups(){
        $group = ItemGroup::all();
        return $group;
    }
    public function insert(Request $request){
        $category = new Category();
        $category->name=$request->input('name');
        $category->save();
    }
    public function insertTypes(Request $request){
        $alltype = new AllTypes();
        $group = ItemGroup::where('name',$request['itemgroup'])->first();
        $alltype->name=$request->input('name');
        $alltype->group_id=$group->id;
        $alltype->save();
    }

    public function sendCode(Request $request)
    {
        $code = rand(1000,9999);
        $output = preg_replace("/^0/", "+254", $request->phone);
        $user = User::where('phone',$output)->first();
            $user->update([
                'code' => $code
            ]);
        $username   = "mduka.com";
        $apiKey     = "04264f63d8b96a3880887e8e40499d6b05bde13cb2454ced59a369500a5a686e";
        $AT         = new AfricasTalking($username, $apiKey);
        $sms        = $AT->sms();
        $recipients = $request->phone;
        $message    = "Verification code ".$code;
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
                'message' => 'Code has been sent',
            ], 201);

    }

    public function changePassword(Request $request)
    {
        $usera = User::where('code',$request['code'])->first();
        $tokenResult = $usera->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        $usera->update([
            'password'=>Hash::make($request['newpass']),
			'code'=>null
        ]);

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'user' => $usera,
            'message'=>"Password changed successfuly",
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
//    public function confirmSignUp(Request $request)
//    {
//        $user = User::where('phone',$request['phone'])->first();
//        if (Carbon::parse($user->created_at)->diffInMinutes(Carbon::now()) <5) {
//            if ($user->code == $request['code'] && $user->created_at){
//				$user->update([
//				     'code'=>null
//				]);
//                return response()->json([
//                    'message' => 'Sign up successful',
//                ],201);
//            }else{
//                return response()->json([
//                    'message' => 'Wrong code',
//                ],201);
//            }
//        }
//        return  response()->json([
//            'message' => 'Verification code has expired',
//        ],201);
//    }

    public function resendCode(Request $request)
    {

    }
}
