<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Category;
use App\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function insert(Request $request){
        $quiz = new Question();
        $quiz->question=$request->input('question');
        $quiz->answer=$request->input('answer');
        $quiz->save();
    }
    public function getQA(){
        $question = Question::all();
        return $question;
    }
}
