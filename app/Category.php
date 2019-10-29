<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function items(){
        return $this->hasMany(Item::class,'category_id');
    }

    public function getItemsAttribute()
    {
        return Item::whereCategoryId($this->id)->get();
    }
    public function getGroupsAttribute() {
        $groups = [];
        $items = Item::where('category_id',$this->id)->get();

        foreach ($items as $item){
            if(!in_array($item->group, $groups)) {
                array_push($groups, $item->group);
            }
        }
        return $groups;

    }

    public function getTypesAttribute() {
        $types = [];
        $items = Item::where('category_id',$this->id)->get();

        foreach ($items as $item){
            array_push($types,AllTypes::where('id',$item->type_id)->first());
        }
        return array_unique($types);

    }

}
