<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $this->call([UserSeeder::class]);
        $this->call([PhoneCountrySeeder::class]);
        $this->call([ProvinceSeeder::class]);
        $this->call([CitySeeder::class]);
        $this->call([EmployeeSeeder::class]);
        $this->call([EducationSeeder::class]);
        $this->call([CredentialSeeder::class]);
        $this->call([KnowFieldSeeder::class]);
        $this->call([CategorySeeder::class]);
        $this->call([FollowCategorySeeder::class]);
        $this->call([FollowUserSeeder::class]);
        $this->call([PostingSeeder::class]);

    }
}
