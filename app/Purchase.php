<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
protected $fillable=['quantity','buying_price','item_id','selling_price','purchase_image_id','size','total'];
    public function item()
    {
        return $this->belongsTo(Item::class, 'itemid');
    }

    public function sales()
    {
        return $this->belongsTo(Item::class, 'quantity');
    }
}
