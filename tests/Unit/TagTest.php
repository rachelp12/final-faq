<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTest extends TestCase
{
    /**
     * test save tag
     *
     * @return void
     */
    public function testSaveTag()
    {
        $tag = factory(\App\Tag::class)->make();
        $this->assertTrue($tag -> save());
    }

    /*
     * test associate tag with question
     */
    public function testAssociateTagWithQuestion()
    {
        $user = $user = factory(\App\User::class)->make();
        $user->save();
        $question = factory(\App\Question::class)->make();
        $question->user()->associate($user);
        $question->save();

        $tag = factory(\App\Tag::class)->make();
        $tag -> save();

        $question->tags()->attach($tag->id);

        $this->assertNotNull($question->tags()->get());
    }

}
