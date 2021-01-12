<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Policies\AnswerPolicy;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_notnot_delete_answers()
    {
        $this->withExceptionHandling();

        $answer = create(Answer::class);

        $this->delete(route('answers.destroy', ['answer'=>$answer]))
            ->assertRedirect('login');
    }

    /** @test */
    public function unauthorized_users_cannot_delete_answers()
    {
        $this->signIn()->withExceptionHandling();

        $answer = create(Answer::class);

        $this->delete(route('answers.destroy', ['answer'=>$answer]))
            ->assertStatus(403);

        $this->assertDatabaseHas('answers',['id'=>$answer->id]);
    }

    /** @test */
    public function authorized_user_can_delete_answers()
    {
        $this->signIn();

        $answer = create(Answer::class, ['user_id'=> auth()->id()]);

        $this->delete(route('answers.destroy', ['answer'=>$answer]))->assertStatus(302);

        $this->assertDatabaseMissing('answers', ['id'=>$answer->id]);
    }

}
