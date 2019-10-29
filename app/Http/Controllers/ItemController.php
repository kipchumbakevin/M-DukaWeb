<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\Category;
use App\Item;
use App\ItemGroup;
use App\ItemProperty;
use App\Payments;
use App\Purchase;
use App\PurchaseImage;
use App\Sales;
use App\Size;
use App\Type;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function insert(Request $request)
    {
        $category = Category::where('name', $request->input('category'))->first();
        $types = AllTypes::where('name', $request->input('type'))->first();
        $itemgroup = ItemGroup::where('name', $request->input('item_group'))->first();

        $item = new Item();
        $item->name = $request->input('name');
        $item->store_id = 1;
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

//        $category = new Category();
//        $category->name=$request->input('category');
//        $category->save();


        $this->validate($request, [

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $new_image = $request->file('image');
        $imagename = time() . '.' . $new_image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $new_image->move($destinationPath, $imagename);

        $p_image = new PurchaseImage();
        $p_image->item_id = $new_item->id;
        $p_image->imageurl = $imagename;
        $p_image->save();

        $new_p_image = PurchaseImage::orderby('created_at', 'desc')->first();

        $purchase = new Purchase();
        $purchase->item_id = $new_item->id;
        $purchase->size_id = 1;
        $purchase->size = $request->input('size');
        $purchase->quantity = $request->input('quantity');
        $purchase->buying_price = $request->input('buyingprice');
        $purchase->selling_price = $request->input('sellingprice');
        $purchase->total = $request->input('quantity') * $request->input('buyingprice');
        $purchase->save();

        return response()->json([
            'message' => 'Added successfully',
        ], 201);
    }

    public function itemedit(Request $request)
    {
        $purchase = Purchase::find($request['item_id']);
        $item = Item::find($request['item_id']);
        $itemproperties = ItemProperty::find($request['item_id']);
//        $item = new Purchase();
//        $item->item_id=$request->input('itemid');
//        $item->quantity=$request->input('quantity');
        $purchase->update([
            'quantity' => $request['quantity'],
            'size' => $request['size'],
            'selling_price'=>$request['sellingprice']
        ]);
        $item->update([
            'name'=>$request['name']

        ]);
        $itemproperties->update([
            'color'=>$request['color'],
            'design'=>$request['design'],
            'company'=>$request['company']
        ]);
    }

    public function deleteItem(Request $request)
    {
        $purchase = Purchase::find($request['item_id']);
        $item = Item::find($request['item_id']);
        $itemproperties = ItemProperty::find($request['item_id']);
        $purchaseimage = PurchaseImage::find($request['item_id']);
//        $item = new Purchase();
//        $item->item_id=$request->input('itemid');
//        $item->quantity=$request->input('quantity');
        $purchase->delete();
        $item->delete();
        $itemproperties->delete();
        $purchaseimage->delete();
    }

}
