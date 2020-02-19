<?php

namespace App\Http\Controllers;

use App\Ads;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function insert(Request $request)
    {
        $this->validate($request, [

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);
        $image = $request->file('image');
        $imagename =rand(100000,999999).time().'.'. $image->getClientOriginalExtension();
        $image->move(public_path().'/images/', $imagename);
        $ad_image = new Ads();
        $ad_image->imageurl =$imagename;
        $ad_image->save();
        return response()->json([
            'message'=>'Added successfully',
        ],201);

    }

    public function getAll()
    {
        $ads = Ads::all();
        return $ads;
    }
}
