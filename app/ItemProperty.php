<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemProperty extends Model
{
    public function item(){
        return $this->belongsTo(Item::class,'itemid');
    }
}
