<?php

namespace Tests\Feature;

use App\Question;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostAnswersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_post_an_answer_to_a_published_question()
    {
        // 假设已存在某个问题
        $question = factory(Question::class)->state('published')->create();
        $user = factory(User::class)->create();

        $response = $this->post("/questions/{$question->id}/answers",[
            "user_id" => $user->id,
            "content" => "this is an answer"
        ]);

        $response->assertStatus(201);
        $answer = $question->answers()->where('user_id', $user->id)->first();

        $this->assertNotNull($answer);

        $this->assertEquals(1, $question->answers()->count());
    }

    /** @test */
    public function can_not_post_an_answer_to_an_unpublished_question()
    {
        $question = factory(Question::class)->state('unpublished')->create();
        $user = factory(User::class)->create();

        $response = $this->withExceptionHandling()
            ->post("/questions/{$question->id}/answers", [
               'user_id'=> $user->id,
               'content'=> 'this is an answer',
            ]);

        $response->assertStatus(404);

        $this->assertDatabaseMissing('answers', ['question_id'=>$question->id]);
        $this->assertEquals(0, $question->answers()->count());
    }
}
