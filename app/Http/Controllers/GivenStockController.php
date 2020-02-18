<?php

namespace App\Http\Controllers;

use App\GivenStock;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GivenStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function insert(Request $request){
        $give = new GivenStock();
        $id = $request->input('item_id');
        $q =$request->input('quantity');
        $ga = [];
        $gall = GivenStock::all();
        foreach ($gall as $gi){
            array_push($ga,$gi->item_id);
        }
        if (in_array($id,$ga)){
            $given = GivenStock::where('item_id',$id)->first();
            $given->update( ['quantity'=>($given->quantity)+$q]);
            return response()->json([
                'message'=>'Added successfully',
            ],201);
        }
        else{
            $give->item_id = $id;
            $give->quantity=$q;
            $give->save();
            return response()->json([
                'message'=>'Added successfully',
                'error'=>false
            ],201);
        }

    }
    public function fetchGiven(Request $request){
        $namecategory = $request['namecategory'];
        $itemdata = Item::join('item_properties','items.id','=','item_properties.item_id')
            ->join('categories','items.category_id','=','categories.id')
            ->join('item_groups','items.item_group_id','=','item_groups.id')
            ->join('all_types','items.type_id','=','all_types.id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchase_images.item_id','=','items.id')
            ->join('given_stocks','given_stocks.item_id','=','items.id')
            ->select('items.*','item_properties.color as color','item_properties.design as design',
                'item_properties.company as company','purchases.size as size','purchases.quantity as quantity',
                'purchases.selling_price as sellingprice',
                'purchase_images.imageurl as image','purchases.id as purchaseId','all_types.name as typeName','given_stocks.id as givenId','given_stocks.quantity as givenQuantity')->
            where('categories.name',$namecategory)
            ->where('items.user_id',Auth::user()->id)
            ->get();
        return $itemdata ;
    }
    public function deleteFromGiven(Request $request){
        //dd(ShoppingList::where('id',$request['id'])->first());
        $give = GivenStock::where('id',$request['id'])->first();
        $give->delete();
        return response()->json([
            'message' => 'Deleted successfully',
        ], 201);
    }

    public function editGiven(Request $request){
        $gg = GivenStock::where('id',$request['id'])->first();
        $gg->update([
            'quantity'=>$request['quantity']
        ]);
    }
}
