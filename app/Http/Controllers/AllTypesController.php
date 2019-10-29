<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\Item;
use App\ItemGroup;
use App\Type;
use Illuminate\Http\Request;

class AllTypesController extends Controller
{
    public function insert(Request $request){
        $alltype = new AllTypes();
        $group = ItemGroup::where('name',$request['itemgroup'])->first();
        $alltype->name=$request->input('name');
        $alltype->group_id=$group->id;
        $alltype->save();
    }
    public function getAllTypes(){
        $alltypes = AllTypes::all();
        return $alltypes;
    }
    public function get_types_item(Request $request)
    {

        $name = $request['name'];
        $namecategory = $request['namecategory'];
        $itemdata = Item::join('item_properties','items.id','=','item_properties.item_id')
            ->join('categories','items.category_id','=','categories.id')
            ->join('item_groups','items.item_group_id','=','item_groups.id')
            ->join('all_types','items.type_id','=','all_types.id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchase_images.item_id','=','items.id')
            ->select('items.*','item_properties.color as color','item_properties.design as design',
                'item_properties.company as company','purchases.size as size','purchases.quantity as quantity',
                'purchase_images.imageurl as image','purchases.id as purchaseId','all_types.name as typeName')->
            where('categories.name',$namecategory)
            ->where('all_types.name',$name)
            ->get();

//        dd($itemdata);

        return $itemdata ;
    }
}
