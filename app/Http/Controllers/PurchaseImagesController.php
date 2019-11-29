<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemProperty;
use App\PurchaseImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseImagesController extends Controller
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
