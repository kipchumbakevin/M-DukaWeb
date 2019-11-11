<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\Category;
use App\ItemGroup;
use App\User;
use Illuminate\Http\Request;

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
        $user = User::where('phone',$request['phone']);
        $user->update([
            'code'=>$code
    ]);
        return response()->json([
            'message' => 'Code has been sent',
        ],201);
    }
}
