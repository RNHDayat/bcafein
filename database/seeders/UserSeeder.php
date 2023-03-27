<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
        $limit = 30;
        for ($i = 1; $i <= $limit; $i++) {
            User::insert([
                'username' => $faker->userName(),
                'account_name' => $faker->firstName(),
                'email' => $faker->email(),
                'password' => Hash::make('harusbisa1')
            ]);
        }

        /**
         * Plotted dummy data for development purposes
         * Please custom these data as needs
         */
        $user = [
            [
                'username' => 'firdaus',
                'account_name' => 'direksi',
                'email' => 'firdaus@untag-sby.ac.id',
                'level' => 0,
                'status' => 1,
                'private_account' => 1,
                'password' => Hash::make('harusbisa1')
            ],
            [
                'username' => 'superadmin',
                'account_name' => 'LordAdmin',
                'email' => 'ptumdi1@gmail.com',
                'level' => 1,
                'status' => 1,
                'password' => Hash::make('harusbisa1')
            ],
            [
                'username' => 'jamalludin',
                'account_name' => 'admin1',
                'email' => 'zainulrifqi100@gmail.com',
                'level' => 2,
                'status' => 1,
                'password' => Hash::make('harusbisa1')
            ],
            [
                'username' => 'suhunya',
                'account_name' => 'kepalaksb',
                'email' => 'suhunya@ilmu.ac.id',
                'level' => 3,
                'status' => 1,
                'password' => Hash::make('harusbisa1')
            ],
            [
                'username' => 'kader',
                'account_name' => 'kadernya',
                'email' => 'kader@ilmu.ac.id',
                'level' => 4,
                'status' => 1,
                'password' => Hash::make('harusbisa1')
            ],
            [
                'username' => 'siloam05',
                'account_name' => 'siloamwahyu',
                'email' => 'siloamwahyu05@gmail.com',
                'level' => 5,
                'status' => 1,
                'password' => Hash::make('shalom123')
            ],
            [
                'username' => 'agungkur',
                'account_name' => 'Agung Kukuruyukk',
                'email' => 'agungkur24202@gmail.com',
                'level' => 5,
                'status' => 1,
                'password' => Hash::make('harusbisa1')
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
