<?php

namespace App\Http\Controllers;

use App\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function insert(Request $request){
        $size = new Size();
        $size->name=$request->input('name');
        $size->save();
    }
}
