<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function item(){
        return $this->belongsTo(Item::class,'itemid');
    }
}
