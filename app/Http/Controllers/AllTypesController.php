<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\Item;
use App\Type;
use Illuminate\Http\Request;

class AllTypesController extends Controller
{
    public function insert(Request $request){
        $alltype = new AllTypes();
        $alltype->name=$request->input('name');
        $alltype->save();
    }
    public function getAllTypes(){
        $alltypes = AllTypes::all();
        return $alltypes;
    }
    public function get_types_item(Request $request)
    {

        $id = $request['type_id'];
        $itemdata = Item::join('item_properties','items.id','=','item_properties.item_id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchase_images.item_id','=','items.id')
            ->select('items.*','item_properties.color as color','item_properties.design as design',
                'item_properties.company as company','purchases.size as size','purchases.quantity as quantity',
                'purchase_images.imageurl as image')->
            where('items.type_id',$id)
            ->get();

//        dd($itemdata);

        return $itemdata ;
    }
}
