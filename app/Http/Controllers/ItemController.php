<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\BuyingPrice;
use App\Category;
use App\Item;
use App\ItemGroup;
use App\ItemImage;
use App\ItemProperty;
use App\Payments;
use App\Purchase;
use App\PurchaseImage;
use App\Sales;
use App\Size;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function insert(Request $request)
    {
        $userid = $request->user()->id;
        $category = Category::where('name', $request->input('category'))->first();
        $types = AllTypes::where('name', $request->input('type'))->first();
        $itemgroup = ItemGroup::where('name', $request->input('item_group'))->first();

        $item = new Item();
        $item->name = $request->input('name');
        $item->user_id = $userid;
        $item->category_id = $category->id;
        $item->type_id = $types->id;
        $item->item_group_id = $itemgroup->id;
        $item->save();

        $new_item = Item::orderby('created_at', 'desc')->first();

        $itemproperty = new ItemProperty();
        $itemproperty->item_id = $new_item->id;
        $itemproperty->color = $request->input('color');
        $itemproperty->design = $request->input('design');
        $itemproperty->company = $request->input('company');
        $itemproperty->save();


        $bp = new BuyingPrice();
        $bp->item_id = $new_item->id;
        $bp->amount=$request->input('buyingprice');
        $bp->save();


        $purchase = new Purchase();
        $purchase->item_id = $new_item->id;
        $purchase->size = $request->input('size');
        $purchase->quantity = $request->input('quantity');
        $purchase->selling_price = $request->input('sellingprice');
        $purchase->total = $request->input('quantity') * $request->input('buyingprice');
        $purchase->save();
		$this->validate($request, [

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $image = $request->file('image');
            $imagename =rand(100000,999999).time().'.'. $image->getClientOriginalExtension();
            $image->move(public_path().'/images/', $imagename);
        $image2 = $request->file('image2');
        $imagename2 = rand(100000,999999).time().'.'.$image2->getClientOriginalExtension();
        $image2->move(public_path().'/images/', $imagename2);

        $p_image = new PurchaseImage();
        $p_image->item_id = $new_item->id;
        $p_image->imageurl =$imagename;
        $p_image->save();

        $item_image = new ItemImage();
        $item_image->item_id = $new_item->id;
        $item_image->imageurl =$imagename;
        $item_image->save();

        $item_image2 = new ItemImage();
        $item_image2->item_id = $new_item->id;
        $item_image2->imageurl =$imagename2;
        $item_image2->save();
        return response()->json([
            'message' => 'Added successfully',
        ], 201);
    }

    public function itemedit(Request $request)
    {
        $purchase = Purchase::find($request['item_id']);
        $item = Item::find($request['item_id']);
//        $itemproperties = ItemProperty::find($request['item_id']);
        $purchase->update([
//            'quantity' => $request['quantity'],
//            'size' => $request['size'],
            'selling_price'=>$request['sellingprice']
        ]);
        $item->update([
            'name'=>$request['name']

        ]);
//        $itemproperties->update([
//            'color'=>$request['color'],
//            'design'=>$request['design'],
//            'company'=>$request['company']
//        ]);
        return response()->json([
            'message' => 'edited successfully',
        ], 201);
    }

    public function newPurchase(Request $request){
        $purchase = Purchase::find($request['item_id']);
        $bp = new BuyingPrice();
        $bpall = BuyingPrice::all()->where('item_id',$request['item_id']);
        $bparray = [];
        foreach ($bpall as $bpa){
            array_push($bparray,$bpa->amount);
        }
        if (!in_array($request['buyingp'],$bparray)) {
            $purchase->update([
                'quantity' => ($purchase->quantity + $request['quantity'])
            ]);
            $purchase->save();
            $bp->item_id = $request->input('item_id');
            $bp->amount = $request->input('buyingp');
            $bp->save();
            return response()->json([
                'message' => 'edited successfully',
            ], 201);
        }else{
            $purchase->update([
                'quantity' => ($purchase->quantity + $request['quantity'])
            ]);
            return response()->json([
                'message' => 'edited successfully',
            ], 201);
        }

    }
    public function deleteItem(Request $request)
    {
        $purchase = Purchase::where('item_id',$request['item_id'])->delete();
        $item = Item::where('id',$request['item_id'])->delete();
        $itemproperties = ItemProperty::where('item_id',$request['item_id'])->delete();
        $purchaseimage = PurchaseImage::where('item_id',$request['item_id'])->delete();
        $bb = BuyingPrice::where('item_id',$request['item_id'])->delete();

        return response()->json([
            'message' => 'Deleted successfully',
        ], 201);
    }

}
