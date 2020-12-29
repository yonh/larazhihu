<?php

namespace Tests\Feature;

use App\Answer;
use App\Question;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BestAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_mark_best_answer()
    {
        $question = create(Question::class);
        $answers = create(Answer::class, ['question_id'=>$question->id],2);

        $this->withExceptionHandling()
            ->post(route('best-answers.store', ['answer'=>$answers[1]]), [$answers[1]])
            ->assertRedirect('/login');
    }

    /** @test */
    public function can_mark_one_answer_as_the_best()
    {
        $this->signIn();

        $question = create(Question::class,['user_id'=> auth()->id()]);

        $answer = create(Answer::class, ['question_id'=>$question->id], 2);

        $this->assertFalse($answer[0]->isBest());
        $this->assertFalse($answer[1]->isBest());

        $this->postJson(route('best-answers.store', ['answer'=>$answer[1]]), [$answer[1]]);

        $this->assertFalse($answer[0]->fresh()->isBest());
        $this->assertTrue($answer[1]->fresh()->isBest());

    }
}
