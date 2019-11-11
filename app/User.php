<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'location','first_name','last_name', 'username','phone', 'password','code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function items(){
        return $this->hasMany(Item::class,'user_id');
    }

    public function getSalesAttribute()
    {
        $sales = Sales::where('user_id',$this->id)->get();
        $allsales = [];
        foreach ($sales as $sale){
            array_push($allsales,$sale);
        }
        return $allsales;
//        $items = Item::where('user_id',$this->id)->get();
////        dd($items);
//        $purchases = [];
//        $sales =[];
//        foreach ($items as $item){
//            array_push($purchases,Purchase::where('item_id',$item->id)->first());
//        }
//
//        foreach ($purchases as $purchase){
//            array_push($sales,Sales::where('purchase_id',$purchase->id)->get());
//        }
////        dd($sales);
//
//        return $sales;
    }

    public function getPricesAttribute()
    {
        $buyingp = BuyingPrice::where('user_id',$this->id)->get();
        $allprices = [];
        foreach ($buyingp as $buyingprice){
            if (!in_array($buyingprice,$allprices)) {
                array_push($allprices, $buyingprice);
            }
        }
        return $allprices;
    }
}
