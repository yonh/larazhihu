<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index()
    {

    }

    public function show($questionId)
    {
//        $question = Question::whereNotNull('published_at')->findOrFail;
//        $question = Question::whereNotNull('published_at')->findOrFail($questionId);
        $question = Question::published()->findOrFail($questionId);
//        return view('questions.show', compact('question'));

        return view('questions.show', [
            'question'=>$question,
            'answers'=>$question->answers()->paginate(20)
        ]);
    }
}
