<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\Item;
use App\ItemGroup;
use App\Purchase;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAllTypes(){
        $alltypes = AllTypes::all();
        return $alltypes;
    }
    public function get_types_item(Request $request)
    {

        $nametype = $request['nametype'];
        $namecategory = $request['namecategory'];
        $itemdata = Item::join('item_properties','items.id','=','item_properties.item_id')
            ->join('categories','items.category_id','=','categories.id')
            ->join('item_groups','items.item_group_id','=','item_groups.id')
            ->join('all_types','items.type_id','=','all_types.id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchase_images.item_id','=','items.id')
            ->select('items.*','item_properties.color as color','item_properties.design as design',
                'item_properties.company as company','purchases.size as size','purchases.quantity as quantity',
                'purchases.selling_price as sellingprice',
                'purchase_images.imageurl as image','purchases.id as purchaseId','all_types.name as typeName')->
            where('categories.name',$namecategory)
            ->where('all_types.name',$nametype)
            ->where('items.user_id',Auth::user()->id)
            ->get();

        return $itemdata ;
    }
    public function get_suggested_restock(Request $request)
    {
        $pp = Purchase::all('quantity');
        $ppp = $pp->values();
        $namecategory = $request['namecategory'];
        $itemdata = Item::join('item_properties','items.id','=','item_properties.item_id')
            ->join('categories','items.category_id','=','categories.id')
            ->join('item_groups','items.item_group_id','=','item_groups.id')
            ->join('all_types','items.type_id','=','all_types.id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchase_images.item_id','=','items.id')
            ->select('items.*','item_properties.color as color','item_properties.design as design',
                'item_properties.company as company','purchases.size as size','purchases.quantity as quantity',
                'purchases.selling_price as sellingprice',
                'purchase_images.imageurl as image','purchases.id as purchaseId','all_types.name as typeName')->
                where($ppp<= Purchase::FIGURE)->
            where('categories.name',$namecategory)
            ->where('items.user_id',Auth::user()->id)
            ->get();

        return $itemdata ;
    }
}
