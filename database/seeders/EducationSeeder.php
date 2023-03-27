<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EducationSeeder extends Seeder
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
        $dasSchool = ['University of Superius Educatio', 'University of Superius Educatio Magisteriferia', 'University of Superius Educatio Doctoriea', 'University of Superius Educatio', 'Das ist der school'];
        $dasStrata = [0, 1, 2, 3, 4];
        $dasDegree = ['B.XX', 'M.XX', 'Ph.D', "CXXn", "XXX"];
        for ($i = 1; $i <= $limit; $i++) {
            $strata = $faker->randomElement($dasStrata);
            for ($lvl=0; $lvl <= $strata ; $lvl++) {
                Education::insert([
                    'id_employee' => $i,
                    'school' => $dasSchool[$lvl],
                    'primary_major' => "main majority of education",
                    'strata' => $dasStrata[$lvl],
                    'degree_type' => $dasDegree[$lvl],
                    'graduation_year' => $faker->numberBetween(2000, 2023),
                ]);
            }
        }
    }
}
