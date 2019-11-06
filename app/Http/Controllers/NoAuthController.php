<?php

namespace App\Http\Controllers;

use App\Category;
use App\ItemGroup;
use Illuminate\Http\Request;

class NoAuthController extends Controller
{
    public function getCategories(){
        $categories = Category::all();
        return $categories;
    }
    public function getGroups(){
        $group = ItemGroup::all();
        return $group;
    }
}
