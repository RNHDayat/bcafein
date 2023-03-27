<?php

namespace Database\Seeders;

use App\Models\FollowUser;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FollowUserSeeder extends Seeder
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
            $countCat = $faker->numberBetween(1, 37);
            for ($lvl = 1; $lvl <= $countCat; $lvl++) {
                FollowUser::insert([
                    'following_id' => $faker->numberBetween(1, 37),
                    'id_user' => $i,
                    'follow_status' => $faker->randomElement([FollowUser::isWAITING,FollowUser::isFOLLOWED,FollowUser::isBLOCKED,FollowUser::isUNFOLLOWED]),
                ]);
            }
        }
    }
}
