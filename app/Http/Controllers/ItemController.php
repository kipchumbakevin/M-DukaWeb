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
    public function insert (Request $request){
        $category=Category::where('name',$request->input('category'))->first();
        $types=AllTypes::where('name',$request->input('type'))->first();
        $itemgroup = ItemGroup::where('name',$request->input('item_group'))->first();

        $item = new Item();
        $item->name=$request->input('name');
        $item->store_id=1;
        $item->category_id=$category->id;
        $item->type_id=$types->id;
        $item->item_group_id=$itemgroup->id;
        $item->save();

        $new_item = Item::orderby('created_at','desc')->first();

        $itemproperty = new ItemProperty();
        $itemproperty->item_id=$new_item->id;
        $itemproperty->color=$request->input('color');
        $itemproperty->design=$request->input('design');
        $itemproperty->company=$request->input('company');
        $itemproperty->save();

//        $category = new Category();
//        $category->name=$request->input('category');
//        $category->save();


        $this->validate($request, [

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $new_image = $request->file('image');
        $imagename = time().'.'.$new_image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $new_image->move($destinationPath,$imagename);

        $p_image = new PurchaseImage();
        $p_image->item_id=$new_item->id;
        $p_image->imageurl = $imagename;
        $p_image->save();

        $new_p_image = PurchaseImage::orderby('created_at','desc')->first();

        $purchase=new Purchase();
        $purchase->item_id=$new_item->id;
        $purchase->size_id=1;
        $purchase->size=$request->input('size');
        $purchase->quantity=$request->input('quantity');
        $purchase->buying_price=$request->input('buyingprice');
        $purchase->selling_price=$request->input('sellingprice');
        $purchase->total=$request->input('quantity') * $request->input('buyingprice');
        $purchase->save();

		return response()->json([
            'message'=>'Added successfully',
        ],201);
    }
    public function itemedit(Request $request){
        $purchase = Purchase::find($request['item_id']);
        $item = new Purchase();
        $item->item_id=$request->input('itemid');
        $item->quantity=$request->input('quantity');
        $purchase->update(
            ['quantity'=>$request->input('quantity')]);
    }
    public function getItems(){
        $itemdata = Item::join('categories','items.category_id','=','categories.id')
            ->join('types','items.type_id','=','types.id')
            ->join('item_properties','items.id','=','item_properties.item_id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchases.purchase_image_id','=','purchase_images.id')
            ->select('items.*','categories.name as category','types.name as type','item_properties.color as color','item_properties.design as design','item_properties.company as company','purchases.size as size','purchases.quantity as quantity','purchase_images.imageurl as image')
            ->get();

//        dd($itemdata[0]->image_url);


        return $itemdata;
    }

}
