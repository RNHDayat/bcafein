<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $limit = 5;
        for ($i = 1; $i <= $limit; $i++) {
            for ($item=0; $item <= 2; $item++) {
                Category::insert([
                    'codeCategory' => $faker->regexify('[A-Za-z0-9]{5}'),
                    'name' => "This is Category of ".strval($item)."-".strval($i),
                    'validation' => Category::isVALIDATED,
                    'id_Ilmu' => $i,
                ]);
            }
        }
    }
}
