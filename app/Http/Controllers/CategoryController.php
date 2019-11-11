<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\Category;
use App\Item;
use App\ItemGroup;
use App\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get_categories_type(Request $request)
    {
        $namecategory = $request['namecategory'];
        $namegroup = $request['namegroup'];
        $itemdata = Item::join('all_types','items.type_id','=','all_types.id')
            ->join('item_groups','all_types.group_id','=','item_groups.id')
            ->join('categories','items.category_id','=','categories.id')
            ->select('all_types.name as typeName')
            ->where('item_groups.name',$namegroup)
            ->where('categories.name',$namecategory)
            ->where('items.user_id',Auth::user()->id)
            ->groupBy('all_types.name')->get();

//        dd($itemdata);
//        $category = Category::whereName($request['category_name'])->first();
//        $itemdata = $category->types;
        return $itemdata;
    }
    public function get_groups(Request $request){
        $category = Category::whereName($request['category_name'])->first();
        $groupdata = $category->groups;
        return $groupdata;
    }
}
