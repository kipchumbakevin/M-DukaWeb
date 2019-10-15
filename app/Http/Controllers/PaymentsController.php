<?php

namespace App\Http\Controllers;

use App\Payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function insert(Request $request){
        $payment = new Payments();
        $payment->amount=$request->input('amount');
        $payment->comment=$request->input('expensetype');
        $payment->save();

        return response()->json([
            'message'=>'Expense added successfully',
            'error'=>false,
            'payment'=>$payment
        ],201);
    }
    public function getExpense(){
        $expense = Payments::all();
        return $expense;
    }
}
