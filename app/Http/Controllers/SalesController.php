<?php

namespace App\Http\Controllers;

use App\Item;
use App\Payments;
use App\Purchase;
use App\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function getTotalSummary(Request $request){
        $total = 0;
        $expenseTotal = 0;
        $sale = Sales::all();
        foreach ($sale as $sales){
            if ($sales->createdAt($sales->created_at->format('m'))==$request['month']&& $sales->created_at->format('Y')== $request['year']) {
                $total += $sales->total_profit;
            }
        }
        $expense= Payments::all();
        foreach ($expense as $expenses){
            if($expenses->createdAt($expenses->created_at->format('m'))==$request['month'] && $expenses->created_at->format('Y')==$request['year']) {
                $expenseTotal += $expenses->amount;
            }
        }
        $result = [
            "totalProfit"=>$total,
            "totalExpense"=>$expenseTotal
        ];
        return $result;
    }
    public function getMonthlySales(Request $request){
        $sales = Sales::all();
        $expenses = [];
        foreach ($sales as $sale){
            if ($sale->createdAt($sale->created_at->format('m')) == $request['month'] && $sale->created_at->format('Y') == $request['year']){

                array_push($expenses,$sale);

            }
        }
        return $expenses;
    }

    public function getSalesDetails(Request $request){
        $id = $request['id'];
        $sale = Sales::join('purchases','sales.purchase_id','=','purchases.id')
            ->join('items','purchases.item_id','=','items.id')
            ->select('sales.*','items.name as name')
            ->where('sales.id',$id)
            ->get();
        return $sale;
    }
    public function insert(Request $request){
      $purchase=  Purchase::find($request['purchase_id']);
      $item = Item::find($purchase['item_id']);
        $sale = new Sales();
        $sale->name=$item->name;
        $sale->purchase_id=$request['purchase_id'];
        $sale->unit_price=$request->input('costprice');
        $sale->quantity = $request->input('quantity');
        $sale->buying_price=$purchase->buying_price;
        $sale->total=($request->input('costprice'))*($request->input('quantity'));
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
