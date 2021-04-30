<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create(['category'  => 'berita paroki']);
        Category::create(['category'  => 'renungan']);
        Category::create(['category'  => 'information']);
    }
}
