<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function itemproperty(){
        return $this->hasOne(ItemProperty::class,'itemid');
    }
    public function purchase(){
        return $this->hasMany(Purchase::class,'itemid');
    }

    public function getCategoryAttribute()
    {
        $category = Category::where('id',$this->category_id)->first();

        return $category->name;
    }

    public function getPropertyAttribute()
    {
        $property = ItemProperty::where('item_id',$this->id)->first();
        return $property;
    }

    public function getPurchaseAttribute()
    {
        $purchase = Purchase::where('item_id',$this->id)->first();
        return $purchase;
    }

    public function getImageUrlAttribute()
    {

        $url = PurchaseImage::where('id',$this->getPurchaseAttribute()->purchase_image_id)->first();
        return  $url->imageurl;
    }
}
