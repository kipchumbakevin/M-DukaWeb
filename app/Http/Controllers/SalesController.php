<?php

namespace App\Http\Controllers;

use App\Item;
use App\Payments;
use App\Purchase;
use App\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function getTotalSales(){
        $total = 0;
        $expenseTotal = 0;
        $sale = Sales::all();
        foreach ($sale as $sales){
            $total += $sales->total_profit;
        }
        $expense= Payments::all();
        foreach ($expense as $expenses){
            $expenseTotal += $expenses->amount;
        }
        $result = [
            "totalProfit"=>$total,
            "totalExpense"=>$expenseTotal
        ];
        return $result;
    }
    public function insert(Request $request){
      $purchase=  Purchase::find($request['purchase_id']);
        $sale = new Sales();
        $sale->purchase_id=$request['purchase_id'];
        $sale->unit_price=$request->input('costprice');
        $sale->quantity = $request->input('quantity');
        $sale->buying_price=$purchase->buying_price;
        $sale->total_profit=($request->input('costprice')-$sale->buying_price)*($request->input('quantity'));
        $sale->save();
$purchase->update(
    ['quantity'=>$purchase->quantity-$sale->quantity]);
        return response()->json([
            'message'=>'sale added',
            'error'=>false,
        ],201);
    }
}
