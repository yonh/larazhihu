<?php

namespace Tests\Feature;

use App\Question;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewQuestionsTest extends TestCase
{
    // Laravel 为我们准备了一个 RefreshDatabase 的 trait，当每次运行测试之前，会运行数据库迁移，创建表；
    // 运行完测试之后，回滚数据库迁移，删除表。所以我们只需使用该 trait 即可：
    use RefreshDatabase;

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
//        $this->withoutExceptionHandling();

        // 1. 创建问题
        $question = factory(Question::class)->create(['published_at'=>Carbon::parse('-1 week')]);

        // 2. 访问链接
        $test = $this->get('/questions/' . $question->id);


        // 3. 应该能看到什么内容
        $test->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /** @test */
    public function user_can_view_a_published_question()
    {
        $question = factory(Question::class)->create(['published_at'=>Carbon::parse('-1 week')]);
        $this->get('/questions/' . $question->id)
            ->assertStatus(200)
            ->assertSee($question->title)
            ->assertSee($question->content);
    }

    /** @test */
    public function user_cannot_view_unpublished_question()
    {
        $question = factory(Question::class)->create(['published_at'=>null]);

        $this->withExceptionHandling()
            ->get('/questions/' . $question->id)
            ->assertStatus(404);
    }
}
