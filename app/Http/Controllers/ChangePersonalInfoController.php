<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Cast\Double;
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
            'last_name'=>$request['lastname']
        ]);
        return response()->json([
            'message' => 'changed successfully',
        ], 201);
    }

    public function generateChangePhoneCode(Request $request)
    {
        $codes = rand(1000,9999);
        $user = User::find(Auth::user()->id);
        $phone = $user->phone;
        if ( Hash::check($request['passcode'],$user->password) && $phone==$request['oldphone']){
            $user->update([
                'code'=>$codes
            ]);
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
        $code= $user->code;
        if ($code==$request['code']) {
            $user->update([
                'phone' => $request['newphone'],
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
        if (Hash::check($request['oldpass'],$user->password)) {
            $user->update([
                'password' =>  Hash::make($request['newpass'])
            ]);
            return response()->json([
                'message' => 'success',
            ],201);
        } else {
            return response()->json([
                'message' => 'Invalid credentials',
            ]);
        }
    }

}
