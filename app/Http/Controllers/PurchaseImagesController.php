<?php

namespace App\Http\Controllers;

use App\PurchaseImage;
use Illuminate\Http\Request;

class PurchaseImagesController extends Controller
{
    public function images()
    {
        $images = PurchaseImage::where('item_id',53)->get();
        return $images;
    }
}
