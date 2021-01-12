<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
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

    /** @test */
    public function only_the_question_creator_can_mark_a_best_answer()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $question = create(Question::class, ['user_id'=>auth()->id()]);

        $answer = create(Answer::class, ['question_id'=>$question->id]);

        // 另一个用户登录
        $this->signIn(create(User::class));

        $this->postJson(route('best-answers.store', ['answer'=>$answer]), [$answer])
            ->assertStatus(403);

        $this->assertFalse($answer->fresh()->isBest());
    }
}
