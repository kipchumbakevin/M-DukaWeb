<?php

namespace App\Http\Controllers;

use App\Category;
use App\Item;
use App\ItemProperty;
use App\Payments;
use App\Purchase;
use App\PurchaseImage;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function insert (Request $request){
        $category=Category::where('name',$request->input('category'))->first();
        $item = new Item();
        $item->name=$request->input('name');
        $item->type=$request->input('type');
        $item->store_id=1;
        $item->category_id=$category->id;
        $item->save();

        $new_item = Item::orderby('created_at','desc')->first();

        $itemproperty = new ItemProperty();
        $itemproperty->item_id=$new_item->id;
        $itemproperty->color=$request->input('color');
        $itemproperty->design=$request->input('design');
        $itemproperty->company=$request->input('company');
        $itemproperty->save();

        $category = new Category();
        $category->name=$request->input('category');
        $category->save();


        $this->validate($request, [

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $new_image = $request->file('image');
        $imagename = time().'.'.$new_image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $new_image->move($destinationPath,$imagename);

        $p_image = new PurchaseImage();
        $p_image->imageurl = $imagename;
        $p_image->save();

        $new_p_image = PurchaseImage::orderby('created_at','desc')->first();

        $purchase=new Purchase();
        $purchase->item_id=$new_item->id;
        $purchase->purchase_image_id=$new_p_image->id;
        $purchase->size=$request->input('size');
        $purchase->quantity=$request->input('quantity');
        $purchase->buying_price=$request->input('buyingprice');
        $purchase->selling_price=$request->input('sellingprice');
        $purchase->total=$request->input('quantity') * $request->input('buyingprice');
        $purchase->save();

		return response()->json([
            'message'=>'Product added',
            'error'=>false,
            'item'=>$item
        ],201);

//        $payment = new Payments();
//        $payment->amount=2;
    }
    public function getItems(){
        $itemdata = Item::join('categories','items.category_id','=','categories.id')
            ->join('item_properties','items.id','=','item_properties.item_id')
            ->join('purchases','items.id','=','purchases.item_id')
            ->join('purchase_images','purchases.purchase_image_id','=','purchase_images.id')
            ->select('items.*','categories.name as category','item_properties.color as color','item_properties.design as design','item_properties.company as company','purchases.size as size','purchases.quantity as quantity','purchase_images.imageurl as image')
            ->get();

//        dd($itemdata[0]->image_url);


        return $itemdata;
    }

}
