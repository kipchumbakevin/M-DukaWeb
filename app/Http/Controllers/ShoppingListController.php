<?php

namespace App\Http\Controllers;

use App\Item;
use App\ShoppingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingListController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function insert(Request $request){
	    $id = $request->input('item_id');
	    $q = $request->input('quantity');
	    $sa = [];
	    $sh = ShoppingList::all();
        $shop = new ShoppingList();
        foreach ($sh as $ss){
            array_push($sa,$ss->item_id);
        }
        if (in_array($id,$sa)){
            $ssh = ShoppingList::where('item_id',$id)->first();
            $ssh->update(
              ['quantity'=>$ssh->quantity + $q]
            );
            return response()->json([
                'message'=>'Added successfully',
                'error'=>false
            ],201);
        }
        else{
            $shop->item_id = $id;
            $shop->quantity=$q;
            $shop->save();
            return response()->json([
                'message'=>'Added successfully',
                'error'=>false
            ],201);
        }
    }
    public function fetchShoppingList(Request $request){
        $namecategory = $request['namecategory'];
        $itemdata = Item::join('item_properties','items.id','=','item_properties.item_id')
            ->join('categories','items.category_id','=','categories.id')
            ->join('item_groups','items.item_group_id','=','item_groups.id')
            ->join('all_types','items.type_id','=','all_types.id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchase_images.item_id','=','items.id')
            ->join('shopping_lists','shopping_lists.item_id','=','items.id')
            ->select('items.*','item_properties.color as color','item_properties.design as design',
                'item_properties.company as company','purchases.size as size','purchases.quantity as quantity',
                'purchases.selling_price as sellingprice',
                'purchase_images.imageurl as image','purchases.id as purchaseId','all_types.name as typeName','shopping_lists.id as shoppingId','shopping_lists.quantity as shoppingQuantity')->
            where('categories.name',$namecategory)
            ->where('items.user_id',Auth::user()->id)
            ->get();
        return $itemdata ;
    }
    public function deleteFromList(Request $request){
	    //dd(ShoppingList::where('id',$request['id'])->first());
        $shop = ShoppingList::where('id',$request['id'])->first();
	    $shop->delete();
		  return response()->json([
            'message' => 'Deleted successfully',
        ], 201);
    }

    public function editShopping(Request $request){
	    $sh = ShoppingList::where('id',$request['id'])->first();
	    $sh->update([
	        'quantity'=>$request['quantity']
        ]);
    }
}
