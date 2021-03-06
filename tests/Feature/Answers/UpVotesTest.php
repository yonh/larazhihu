<?php

namespace Tests\Feature\Answers;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UpVotesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_not_vote_up()
    {
        $this->withExceptionHandling()
            ->post('/answers/1/up-votes')
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_vote_up()
    {
        $this->signIn();

        $answer = create(Answer::class);
        $this->post("/answers/{$answer->id}/up-votes")
            ->assertStatus(201);

        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function an_authenticated_user_can_cancel_vote_up()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->post("/answers/{$answer->id}/up-votes");

        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());

        $this->delete("/answers/{$answer->id}/up-votes");

        $this->assertCount(0, $answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function can_vote_up_only_once()
    {

        $this->signIn();

        $answer = create(Answer::class);

        try {
            $this->post("/answers/{$answer->id}/up-votes");
            $this->post("/answers/{$answer->id}/up-votes");
        } catch (\Exception $e) {
            $this->fail('Can not vote up twice.');
        }

        $this->assertCount(1, $answer->refresh()->votes('vote_up')->get());
    }

    /** @test */
    public function can_know_it_is_voted_up()
    {
        $this->signIn();

        $answer = create(Answer::class);

        $this->post("/answers/{$answer->id}/up-votes");
        $this->assertTrue($answer->refresh()->isVotedUp(Auth::user()));
    }

    /** @test */
    public function can_know_up_votes_count()
    {
        $answer = create(Answer::class);

        $this->signIn();

        $this->post("/answers/{$answer->id}/up-votes");
        $this->assertEquals(1, $answer->refresh()->upVotesCount);

        $this->signIn(create(User::class));
        $this->post("/answers/{$answer->id}/up-votes");

        $this->assertEquals(2, $answer->refresh()->upVotesCount);
    }
}
