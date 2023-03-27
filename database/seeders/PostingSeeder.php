<?php

namespace Database\Seeders;

use App\Models\Posting;
use App\Models\Reply;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class PostingSeeder extends Seeder
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
            $countCat = $faker->numberBetween(15,70);
            for ($lvl = 1; $lvl <= $countCat; $lvl++) {
                Posting::insert([
                    'id_user' => $i,
                    'id_credential' => $i,
                    'id_category' => $faker->randomElement([1,2,3,4,5]),
                    'title' => $faker->words(rand(10, 20), true),
                    'title_slug' => $faker->words(rand(3, 10), true),
                    'description' => $faker->paragraph(3),
                    'image' => $faker->imageUrl(640, 480, 'anime', true),
                    'status' => $faker->randomElement([Posting::isHIDDEN,Posting::isACTIVE,Posting::isDRAFTED,Posting::isBEST_ANSWER,Posting::isBLOCKED]),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

            }
        }

        $countPostings = Posting::count();
        for ($i=1; $i <= $countPostings; $i++) {
            $isReplying = $faker->randomElement([0,1]);
            if($isReplying){
                $randomNumber = $faker->numberBetween(2,$countPostings);
                Reply::insert([
                    'id_postings' => $i,
                    'toAnswer_posting' => $randomNumber,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                Reply::insert([
                    'id_postings' => $i,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
