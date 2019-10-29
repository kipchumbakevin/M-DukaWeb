<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\Category;
use App\Item;
use App\ItemGroup;
use App\Payments;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function insert(Request $request){
        $category = new Category();
        $category->name=$request->input('name');
        $category->save();
    }
    public function getCategories(){
        $categories = Category::all();
        return $categories;
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
