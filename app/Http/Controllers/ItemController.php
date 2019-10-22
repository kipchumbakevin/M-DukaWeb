<?php

namespace App\Http\Controllers;

use App\AllTypes;
use App\Category;
use App\Item;
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
        $item = new Item();
        $item->name=$request->input('name');
        $item->store_id=1;
        $item->category_id=$category->id;
        $item->save();

        $new_item = Item::orderby('created_at','desc')->first();
        $type = new Type();
        $alltype = AllTypes::where('name',$request->input('type'))->first();
        $type->type_id=$alltype->id;
        $type->item_id=$new_item->id;
        $type->save();

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
        $p_image->imageurl = $imagename;
        $p_image->save();

        $new_p_image = PurchaseImage::orderby('created_at','desc')->first();

        $size=Size::where('name',$request->input('sizename'))->first();
        $purchase=new Purchase();
        $purchase->item_id=$new_item->id;
        $purchase->purchase_image_id=$new_p_image->id;
        $purchase->size_id=$size->id;
        $purchase->size=$request->input('size');
        $purchase->quantity=$request->input('quantity');
        $purchase->buying_price=$request->input('buyingprice');
        $purchase->selling_price=$request->input('sellingprice');
        $purchase->total=$request->input('quantity') * $request->input('buyingprice');
        $purchase->save();

//        $sale =new Sales();
//        $sale->item_id=$new_item->id;
//        $sale->buying_price=$request->input('buyingprice');
//        $sale->save();

		return response()->json([
            'message'=>'Added successfully',
        ],201);

//        $payment = new Payments();
//        $payment->amount=2;
    }
    public function itemsale(Request $request){
        $iitem = Item::orderby('created_at','desc')->first();
        $sale = new Sales();
        $sale->item_id=$iitem->id;
        $sale->quantity=$request->input('quantity');
        $sale->sold_price=$request->input('costprice');
        $sale->total=$request->input('quantity') * $request->input('costprice');
        $sale->save();
        return response()->json([
            'message'=>'sale added',
            'error'=>false,
        ],201);
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
