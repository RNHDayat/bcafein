<?php

namespace Database\Seeders;

use App\Models\Credential;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CredentialSeeder extends Seeder
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
        // $faker = Faker::create('id_ID');
        $limit = 37;
        for ($i = 1; $i <= $limit; $i++) {
            Credential::insert([
                'id_employee' => $i,
                'type' => 0,
                'description' => "I'm an expert of XXX",
            ]);
        }
    }
}
