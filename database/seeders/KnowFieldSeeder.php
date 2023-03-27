<?php

namespace Database\Seeders;

use App\Models\knowField;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KnowFieldSeeder extends Seeder
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
            knowField::insert([
                'codeIlmu' => $faker->regexify('[A-Za-z0-9]{5}'),
                'name' => "This is Knowledge Field -".strval($i),
                'validation' => knowField::isVALIDATED,
            ]);
        }
    }
}
