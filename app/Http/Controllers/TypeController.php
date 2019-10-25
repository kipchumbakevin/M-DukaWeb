<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use App\ItemProperty;
use App\Purchase;
use App\PurchaseImage;
use App\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function insert(Request $request){
        $type = new Type();
        $type->name=$request->input('name');
        $type->save();
    }
    public function getTypes(){
        $types = Type::all();
        return $types;
    }
    public function get_types_item(Request $request)
    {

//        $name = $request['type_name'];
//        $itemdata = Item::join('types','items.id','=','types.item_id')
//            ->join('item_properties','items.id','=','item_properties.item_id')
//            ->join('purchases','items.id','=','purchases.item_id')
//            ->join('purchase_images','purchases.purchase_image_id','=','purchase_images.id')
//            ->select('items.*','all_types.name as type','item_properties.color as color','item_properties.design as design','item_properties.company as company','purchases.size as size','purchases.quantity as quantity','purchase_images.imageurl as image')
//            ->where('type.name',$name)
//            ->get();
        $type = Type::find($request['id']);
        $property = Item::find($request['id']);
        $purchase = Item::find($request['id']);
        $purchaseimage = Item::find($request['id']);
        $result = [
             $property->items_property,
             $type->items,
            $purchase->items_purchase,
            $purchaseimage->purchase_image

        ];
        return $result ;
    }
}
