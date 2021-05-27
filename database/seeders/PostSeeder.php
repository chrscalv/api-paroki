<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use Carbon;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        foreach (range(1,50) as $i) {
            Post::create([
                'title'         => $faker->name,
                'thumbnail'     => $faker->imageUrl($width = 640, $height = 480),
                'body'          => $faker->text(200),
                'is_published'  => '1',
                'published_at'  => now(),
                'user_id'       => '1',
                'category_id'   => $faker->numberBetween(1,3)
            ]);
        }
    }
}
