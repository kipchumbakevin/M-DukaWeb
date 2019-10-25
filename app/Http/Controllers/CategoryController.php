<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
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

    public function get_categories_item(Request $request)
    {
//        dd($request->all());
//        $name = $request['category_name'];
//        $itemdata = Item::join('categories','items.category_id','=','categories.id')
//            ->join('types','items.id','=','types.item_id')
//            ->join('types','all_types.id','=','types.type_id')
//            ->join('item_properties','items.id','=','item_properties.item_id')
//            ->join('purchases','items.id','=','purchases.item_id')
//            ->join('purchase_images','purchases.purchase_image_id','=','purchase_images.id')
//            ->select('items.*','categories.name as category','all_types.name as type','item_properties.color as color',
//                'item_properties.design as design','item_properties.company as company',
//                'purchases.size as size','purchases.quantity as quantity','purchase_images.imageurl as image')
//            ->where('categories.name',$name)
//            ->get();
        $category = Category::whereName($request['category_name'])->first();
        $itemdata = $category->types;
        return $itemdata;
    }
}
