<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Purchase extends Model
{
    const FIGURE = 2;
protected $fillable=['quantity','buying_price','item_id','selling_price','purchase_image_id','size','total'];
    public function item()
    {
        return $this->belongsTo(Item::class, 'itemid');
    }

    public function sales()
    {
        return $this->belongsTo(Item::class, 'quantity');
    }

    public function getItemsAttribute()
    {
        return Item::select('name')->where('id',$this->item_id)
            ->where('user_id',Auth::user()->id)->get();
    }

}
