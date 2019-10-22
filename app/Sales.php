<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    public function item(){
        return $this->belongsTo(Item::class,'itemid');
    }

    public function purchase(){
        return $this->hasMany(Purchase::class,'quantity');
    }
}
