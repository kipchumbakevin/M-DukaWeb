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
        $shop = new ShoppingList();
        $shop->item_id = $request->input('item_id');
        $shop->quantity=$request->input('quantity');
        $shop->save();
		 return response()->json([
            'message'=>'Expense added successfully',
            'error'=>false
        ],201);
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
                'purchase_images.imageurl as image','purchases.id as purchaseId','all_types.name as typeName','shopping_lists.quantity as shoppingQuantity')->
            where('categories.name',$namecategory)
            ->where('items.user_id',Auth::user()->id)
            ->get();
        return $itemdata ;
    }
    public function deleteFromList(Request $request){
        $shop = ShoppingList::where('id',$request['item_id'])->delete();
    }
	
	   
}
