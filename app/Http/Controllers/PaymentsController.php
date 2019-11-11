<?php

namespace App\Http\Controllers;

use App\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function insert(Request $request){
        $userid = Auth::user()->id;
        $payment = new Payments();
        $payment->user_id = $userid;
        $payment->amount=$request->input('amount');
        $payment->comment=$request->input('expensetype');
        $payment->save();

        return response()->json([
            'message'=>'Expense added successfully',
            'error'=>false,
            'payment'=>$payment
        ],201);
    }
//    public function getExpense(){
//        $expense = Payments::all();
//        return $expense;
//    }
    public function deleteExpense(Request $request){
        $expense = Payments::find($request['id']);
        $expense->delete();
        return response()->json([
            'message'=>'Deleted successfully',
        ],201);
    }
    public function getYear(){
        $expense = Payments::all()->where('user_id',Auth::user()->id);
        $data = [];
        foreach ($expense as $exp){
            $year = $exp->created_at->format('Y');
			if(!in_array(['year'=>$year],$data)){
               array_push($data,['year'=>$year]);
			}
        }
        return $data;
    }
    public function getMonths(Request $request){
        $payments = Payments::all()->where('user_id',Auth::user()->id);;
        $months = [];
        foreach ($payments as $payment){
            $month = $payment->createdAt($payment->created_at->format('m'));
			$year = $payment->created_at->format('Y');
			if(!in_array(['month'=>$month,'year'=>$year],$months)){
            if ($payment->created_at->format('Y') == $request['year']){
                array_push($months,['month'=>$month,'year'=>$year]);
            }
			}
        }
        return $months;

    }
    public function getExpenses(Request $request){
        $payments = Payments::all()->where('user_id',Auth::user()->id);;
        $expenses = [];
        foreach ($payments as $payment){
            if ($payment->createdAt($payment->created_at->format('m')) == $request['month'] && $payment->created_at->format('Y') == $request['year']){

                array_push($expenses,$payment);

            }
        }
        return $expenses;
    }
}
