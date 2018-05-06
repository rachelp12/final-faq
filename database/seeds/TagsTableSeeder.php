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
    }
}
