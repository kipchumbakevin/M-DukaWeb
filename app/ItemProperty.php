<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ItemProperty extends Model
{
    protected $fillable=['color','design','company','item_id'];
    public function item(){
        return $this->belongsTo(Item::class,'itemid');
    }

}
