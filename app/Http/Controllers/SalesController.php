<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemProperty;
use App\Payments;
use App\Purchase;
use App\Sales;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getTotalSummary(Request $request)
    {
        $total = 0;
        $expenseTotal = 0;
        $user = Auth::user();
        $sale = $user->sales;
        foreach ($sale as $sales) {
            if ($sales->createdAt($sales->created_at->format('m')) == $request['month'] && $sales->created_at->format('Y') == $request['year']) {
                $total += $sales->total_profit;
            }
        }
        $expense = Payments::all()->where('user_id',Auth::user()->id);
        foreach ($expense as $expenses) {
            if ($expenses->createdAt($expenses->created_at->format('m')) == $request['month'] && $expenses->created_at->format('Y') == $request['year']) {
                $expenseTotal += $expenses->amount;
            }
        }
        $result = [
            "totalProfit" => $total,
            "totalExpense" => $expenseTotal
        ];
        return $result;
    }

    public function getMonthlySales(Request $request)
    {
        $user = Auth::user();
        $sales = $user->sales;
        $expenses = [];
        foreach ($sales as $sale){
            if ($sale->createdAt($sale->created_at->format('m')) == $request['month'] && $sale->created_at->format('Y') == $request['year']) {
                array_push($expenses, $sale);
            }
        }

        return $expenses;
    }

//    public function getSalesDetails(Request $request){
//        $user = Auth::user();
//        $month = $request['month'];
//        $yyy =$user->sales;
//        $year = $request['year'];
//        $sale = Sales::join('purchases','sales.purchase_id','=','purchases.id')
//            ->join('items','purchases.item_id','=','items.id')
//            ->join('items','item_properties.item_id','=','items.id')
//            ->select('sales.*','items.name as name','item_properties.color as color')
//            ->where($yyy->createdAt($yyy->created_at->format('m'),$month))
//            ->where($yyy->createdAt($yyy->created_at->format('Y'),$year))
//            ->get();
//        return $sale;
//    }
    public function insert(Request $request){
        $userid = Auth::user()->id;
        $items = ItemProperty::where('item_id',$request['item_id'])->first();
        $pp = Purchase::where('item_id',$request['item_id'])->first();
        $purchase=  Purchase::find($request['purchase_id']);
        $sale = new Sales();
        $sale->name=$purchase->items[0]->name;
        $sale->color=$items->color;
        $sale->size = $pp->size;
        $sale->user_id=$userid;
        $sale->purchase_id=$request['purchase_id'];
        $sale->unit_price=$request->input('costprice');
        $sale->quantity = $request->input('quantity');
        $sale->buying_price=$request->input('buyingprice');
        $sale->total=($request->input('costprice'))*($request->input('quantity'));
        $sale->total_profit=($request->input('costprice')-$sale->buying_price)*($request->input('quantity'));
        $sale->save();
$purchase->update(
    ['quantity'=>$purchase->quantity-$sale->quantity]);
        return response()->json([
            'message'=>'sale added',
        ],201);
    }

    public function deleteSale(Request $request){
        $sale = Sales::find($request['id']);
		$sale->delete();
		return response()->json([
            'message'=>'Deleted successfully',
        ],201);
    }
}
