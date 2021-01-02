<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Policies\AnswerPolicy;
use App\Question;
use Illuminate\Http\Request;

class AnswersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($questionId)
    {
        $question = Question::published()->findOrFail($questionId);

        $this->validate(request(), [
            'content'=> 'required'
        ]);

        $question->answers()->create([
            'user_id'=> auth()->id(),
            'content'=>request('content'),
        ]);

        return back();
//        return response()->json([], 201);
    }

    public function destroy(Answer $answer)
    {
        $this->authorize('delete', $answer);

        $answer->delete();

        return back();
    }
}
