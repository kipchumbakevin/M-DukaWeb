<?php

namespace App\Http\Controllers;

use AfricasTalking\SDK\AfricasTalking;
use App\AllTypes;
use App\Category;
use App\Code;
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
        $output = preg_replace("/^0/", "+254", $request->phone);
        $user = User::where('phone',$output)->first();
        if ($user->phone==$output) {
            return response()->json([
                'message' => 'Correct',
            ], 201);
        }else{
            return null;
        }

    }
    public function checkIfUserExists(Request $request)
    {
        $numbers = [];
        $all = [];
        $output = preg_replace("/^0/", "+254", $request->phone);
        $user = User::all('phone','username');
        $usnm = $request['username'];
        foreach ($user as $users){
            array_push($numbers,$users->phone);
            array_push($numbers,$users->username);
        }
        if (in_array($output,$numbers) && in_array($usnm,$numbers)){
            return response()->json([
                'message' => 'The username and phone number have already been taken',
            ]);
        }
        if (in_array($output,$numbers)){
            return response()->json([
                    'message' => 'The phone number has already been taken',
                ]);
        }if (in_array($usnm,$numbers)){
            return response()->json([
                'message' => 'The username has already been taken',
            ]);
        }else{
        return response()->json([
            'message' => 'Confirm code sent',
        ],200);
    }

    }

    public function changePassword(Request $request)
    {
		$output = preg_replace("/^0/", "+254", $request->phone);
        $usera = User::where('phone',$output)->first();
        $codestable = Code::where('code',$request['code'])->first();
        $tokenResult = $usera->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        $usera->update([
            'password'=>Hash::make($request['newpass']),
        ]);
        $codestable->delete();

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
