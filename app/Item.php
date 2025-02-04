<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    protected $fillable = ['name', 'type_id', 'category_id', 'item_group_id', 'store_id'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    public function itemproperty()
    {
        return $this->hasOne(ItemProperty::class, 'itemid');
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'itemid');
    }

    public function sales()
    {
        return $this->hasMany(Sales::class, 'itemid');
    }

    public function getCategoryAttribute()
    {
        $category = Category::where('id', $this->category_id)->first();

        return $category->name;
    }

    public function getTypeAttribute()
    {
        $type = Type::where('id', $this->type_id)->first();

        return $type->name;
    }

    public function getGroupAttribute()
    {
        return ItemGroup::find($this->item_group_id);
    }
    public function getPropertyAttribute()
    {
        $property = ItemProperty::where('item_id', $this->id)->first();
        return $property;
    }

    public function getPurchaseAttribute()
    {
        $purchase = Purchase::where('item_id', $this->id)->first();
        return $purchase;
    }

    public function getImagesAttribute()
    {
        return ItemImage::where('item_id',$this->id)->get();
    }
    public function getImageUrlAttribute()
    {

        $url = PurchaseImage::where('id', $this->getPurchaseAttribute()->purchase_image_id)->first();
        return $url->imageurl;
    }

    public function getItemsPropertyAttribute()
    {
        return ItemProperty::select('color', 'design', 'company')->where('item_id', $this->id)->get();
    }

    public function getItemsPurchaseAttribute()
    {
        return Purchase::select('quantity', 'size', 'selling_price')->where('item_id', $this->id)->get();
    }

    public function getPurchaseImageAttribute()
    {
        return PurchaseImage::select('imageurl')->where('item_id', $this->id)->get();
    }
    public function getColorsAttribute()
    {
       $col = ItemProperty::select('color')->where('item_id',$this->id);
       return $col;
    }
    public function getSizesAttribute()
    {
        return Purchase::select('size')->where('item_id',$this->id);
    }
    //testing
    public function getPropertiesAttribute()
    {
        return ItemProperty::all()->where('item_id',$this->id);
    }

    public function getPurchasesAttribute()
    {
        return Purchase::all()->where('item_id',$this->id);
    }

    public function getPurchaseimagesAttribute()
    {
        return PurchaseImage::all()->where('item_id',$this->id);
    }
}
