<?php

namespace App\Http\Controllers;

use App\AllTypes;
use Illuminate\Http\Request;

class AllTypesController extends Controller
{
    public function insert(Request $request){
        $alltype = new AllTypes();
        $alltype->name=$request->input('name');
        $alltype->save();
    }
    public function getAllTypes(){
        $alltypes = AllTypes::all();
        return $alltypes;
    }
}
