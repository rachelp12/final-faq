<?php

use Illuminate\Database\Seeder;

class TagsTableSeeder extends Seeder
{
    /**
     * Create 50 tags. For each tag, associate it with 5 random questions
     *
     * @return void
     */
    public function run()
    {
        factory(App\Tag::class, 10)->create();
//->each(function ($tag) {
//            for ($i = 1; $i <= 3; $i++) {
//                $questionId = App\Question::inRandomOrder()->first()->value('id');
//                dd($questionId);
//                dd($tag->questions());
//                $tag->questions()->attach($questionId);
//            }
//        });
    }
}
