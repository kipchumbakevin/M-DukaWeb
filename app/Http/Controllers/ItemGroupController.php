<?php

namespace App\Http\Controllers;
use App\ItemGroup;
use Illuminate\Http\Request;

class ItemGroupController extends Controller
{
    public function insert(Request $request){
        $itemgroup = new ItemGroup();
        $itemgroup->name=$request->input('name');
        $itemgroup->save();
    }
    public function get_types(Request $request){
        $category = ItemGroup::whereName($request['group_name'])->first();
        $groupdata = $category->types;
        return $groupdata;
    }
    public function getGroups(){
        $group = ItemGroup::all();
        return $group;
    }
}
