<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemGroup extends Model
{
    public function getTypesAttribute() {
        $alltypes = AllTypes::where('group_id',$this->id)->get();

        return $alltypes;

    }
}
