<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function changePhone(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $phone= $user->phone;
        if ($phone==$request['oldphone']) {
            $user->update([
                'phone' => $request['newphone']
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
        $phone = $user->phone;
        if ($phone == $request['phone']) {
            $user->update([
                'password' =>  bcrypt($request['newpass']),
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
    public function confirmCode(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if ($user->code == $request['code']){
            return response()->json([
                'message' => 'Confirmed',
            ]);
        }
    }
}
