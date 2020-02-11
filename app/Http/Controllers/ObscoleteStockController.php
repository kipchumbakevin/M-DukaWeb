<?php

namespace App\Http\Controllers;

use App\Item;
use App\ObscoleteStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObscoleteStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function insert(Request $request){
        $obsc = new ObscoleteStock();
        $obsc->item_id = $request->input('item_id');
        $obsc->quantity=$request->input('quantity');
        $obsc->save();
        return response()->json([
            'message'=>'Added successfully',
            'error'=>false
        ],201);
    }
    public function fetchObscolete(Request $request){
        $namecategory = $request['namecategory'];
        $itemdata = Item::join('item_properties','items.id','=','item_properties.item_id')
            ->join('categories','items.category_id','=','categories.id')
            ->join('item_groups','items.item_group_id','=','item_groups.id')
            ->join('all_types','items.type_id','=','all_types.id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchase_images.item_id','=','items.id')
            ->join('obscolete_stocks','obscolete_stocks.item_id','=','items.id')
            ->select('items.*','item_properties.color as color','item_properties.design as design',
                'item_properties.company as company','purchases.size as size','purchases.quantity as quantity',
                'purchases.selling_price as sellingprice',
                'purchase_images.imageurl as image','purchases.id as purchaseId','all_types.name as typeName','obscolete_stocks.id as obscoleteId','obscolete_stocks.quantity as obscoleteQuantity')->
            where('categories.name',$namecategory)
            ->where('items.user_id',Auth::user()->id)
            ->get();
        return $itemdata ;
    }
    public function deleteObscolete(Request $request){
        //dd(ShoppingList::where('id',$request['id'])->first());
        $obs = ObscoleteStock::where('id',$request['id'])->first();
        $obs->delete();
        return response()->json([
            'message' => 'Deleted successfully',
        ], 201);
    }
}
