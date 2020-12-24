<?php

namespace Tests\Feature;

use App\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    /** @test */
    public function user_can_view_questions()
    {
        // 0. 抛出异常
        $this->withoutExceptionHandling();

        // 2. 访问链接 /questions
        $test = $this->get('/questions');

        // 3. 正常返回 200
        $test->assertStatus(200);
    }

    /** @test */
    public function user_can_view_a_single_question()
    {
        $this->withoutExceptionHandling();

        // 1. 创建问题
        $question = factory(Question::class)->create();

        // 2. 访问链接
        $test = $this->get('/questions/' . $question->id);


        // 3. 应该能看到什么内容
        $test->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }
}
