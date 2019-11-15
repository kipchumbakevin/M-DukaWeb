<?php

namespace App\Http\Controllers;

use App\BuyingPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyingPriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getBuyingPrice(Request $request)
    {
        $bp = BuyingPrice::all()->where('item_id',$request['item_id']);
        $bb=[];
        foreach ($bp as $b){
			$amount = $b->amount;
            if (!in_array(['amount'=>$amount],$bb)) {
                array_push($bb, ['amount'=>$amount]);
            }
        }
         return $bb;
    }
}
