<?php

namespace Database\Seeders;

use App\Models\FollowCategory;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FollowCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Random generate user as dummy data
         * Please comment these code in production mode
         */
        $faker = Faker::create('id_ID');
        $limit = 37;
        for ($i = 1; $i <= $limit; $i++) {
            $countCat = $faker->numberBetween(2, 15);
            for ($lvl = 1; $lvl <= $countCat; $lvl++) {
                FollowCategory::insert([
                    'id_user' => $i,
                    'follow_cat_id' => $lvl,
                    'follow_status' => $faker->randomElement([FollowCategory::isFOLLOWED,FollowCategory::isUNFOLLOWED,FollowCategory::isBLOCKED]),
                ]);
            }
        }
    }
}
