<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllTypes extends Model
{
    public function items(){
        return $this->hasMany(Item::class,'type_id');
    }
    public function getItemsAttribute(){
        return Item::where('type_id',$this->id)->get();
    }
}
