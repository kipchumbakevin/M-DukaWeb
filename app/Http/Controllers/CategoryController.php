<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function insert(Request $request){
        $category = new Category();
        $category->name=$request->input('name');
        $category->save();
    }
}
