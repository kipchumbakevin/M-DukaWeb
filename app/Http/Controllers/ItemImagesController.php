<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function images(Request $request)
    {
        $item = Item::where('user_id',Auth::user()->id)->where('id',$request['item_id'])->first();
        return $item->images;
    }
}
