<?php

namespace App\Http\Controllers;

use App\Answer;
use Illuminate\Http\Request;

class BestAnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Answer $answer)
    {
        $answer->question->markAsBestAnswer($answer);

        return back();
    }
}