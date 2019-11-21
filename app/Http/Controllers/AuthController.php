<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use AfricasTalking\SDK\AfricasTalking;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $code = rand(1000,9999);
        $request->validate([
            'username' => 'required|string|unique:users',
            'location' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone'=>'required|string|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'first_name' => $request->first_name,
            'last_name'=>$request->last_name,
            'username' => $request->username,
            'phone' => $request->phone,
            'code'=>$code,
            'location' => $request->location,
            'password' => Hash::make($request->password)

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
		$user->save();
        return response()->json([
            'message' => $message
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['username', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Credentials do no match'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'user' => $user,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ],200);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function getUser(Request $request){
        $data = User::all('first_name','last_name','phone');
        return $data;
    }
}
