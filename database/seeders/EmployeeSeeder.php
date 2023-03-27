<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
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
            Employee::insert([
                'id_user' => $i,
                'fullname' => $faker->name(),
                'nickname' => $faker->firstName(),
                'familyname' => $faker->lastName(),
                'gender' => $faker->randomElement(['L','P']),
                'datebirth' => $faker->dateTimeBetween('1960-01-01', '2010-12-31'),
                'phone' => $faker->phoneNumber(),
                'country' => "Indonesia",
                // Current Job
                'company' => $faker->company(),
                'job_position' => $faker->jobTitle(),
                'start_year' => $faker->numberBetween(1980, 2010),
                'end_year' => $faker->numberBetween(2000, 2010),
                // Address Home
                'lat_house' => $faker->latitude(),
                'long_house' => $faker->longitude(),
                'address_house' => $faker->address(),
            ]);
        }

        /**
         * Plotted dummy data for development purposes
         * Please custom these data as needs
         */
        $employees = [
            [
                'id_user' => 31,
                'fullname' => "Muhamad Firdaus, S.T., M.Kom",
                'nickname' => "Fir",
                'gender' => "L",
                'datebirth' => '1975-03-25',
                'phone' => '081231253544',
                'country' => "Indonesia",
                // Current Job
                'company' => "Universitas 17 Agustus 1945 Surabaya",
                'job_position' => "Head of Technic Faculty Quality Assurance",
                'start_year' => '2004',
                // Address Home
                'lat_house' => $faker->latitude(),
                'long_house' => $faker->longitude(),
                'address_house' => $faker->address(),
            ],
            [
                'id_user' => 32,
                'fullname' => "Alvin Permana Emur",
                'nickname' => "Vin",
                'gender' => "L",
                'datebirth' => '1996-09-16',
                'phone' => $faker->phoneNumber(),
                'country' => "Indonesia",
                // Current Job
                'company' => "PT. UMDI",
                'job_position' => "General Manager",
                'start_year' => '2020',
                // Address Home
                'lat_house' => $faker->latitude(),
                'long_house' => $faker->longitude(),
                'address_house' => $faker->address(),
            ],
            [
                'id_user' => 33,
                'fullname' => "Jamalludin",
                'nickname' => "Jamal",
                'gender' => "L",
                'datebirth' => '2001-12-16',
                'phone' => $faker->phoneNumber(),
                'country' => "Indonesia",
                // Current Job
                'company' => "Universitas 17 Agustus 1945 Surabaya",
                'job_position' => "Student",
                'start_year' => '2020',
                // Address Home
                'lat_house' => $faker->latitude(),
                'long_house' => $faker->longitude(),
                'address_house' => $faker->address(),
            ],
            [
                'id_user' => 34,
                'fullname' => "Suhu",
                'nickname' => "Master",
                'gender' => "P",
                'datebirth' => '1990-12-16',
                'phone' => $faker->phoneNumber(),
                'country' => "Indonesia",
                // Current Job
                'company' => "INKINDO",
                'job_position' => "Suhu",
                'start_year' => '2005',
                // Address Home
                'lat_house' => $faker->latitude(),
                'long_house' => $faker->longitude(),
                'address_house' => $faker->address(),
            ],
            [
                'id_user' => 35,
                'fullname' => "Kadernya",
                'nickname' => "Fuhrer",
                'gender' => "L",
                'datebirth' => '1960-04-03',
                'phone' => $faker->phoneNumber(),
                'country' => "Indonesia",
                // Current Job
                'company' => "Reichstag",
                'job_position' => "ReichFuhrer",
                'start_year' => '1987',
                // Address Home
                'lat_house' => $faker->latitude(),
                'long_house' => $faker->longitude(),
                'address_house' => $faker->address(),
            ],
            [
                'id_user' => 36,
                'fullname' => "Siloam Wahyu Wijaya, S.Kom",
                'nickname' => "Silo",
                'gender' => "L",
                'datebirth' => '2001-05-22',
                'phone' => '081332660932',
                'country' => "Indonesia",
                // Current Job
                'company' => "Universitas 17 Agustus 1945 Surabaya",
                'job_position' => "Student",
                'start_year' => '2019',
                // Address Home
                'lat_house' => $faker->latitude(),
                'long_house' => $faker->longitude(),
                'address_house' => $faker->address(),
            ],
            [
                'id_user' => 37,
                'fullname' => "Agung Kurniawan",
                'nickname' => "Gung",
                'gender' => "L",
                'datebirth' => '2002-03-24',
                'phone' => $faker->phoneNumber(),
                'country' => "Indonesia",
                // Current Job
                'company' => "Universitas 17 Agustus 1945 Surabaya",
                'job_position' => "Student",
                'start_year' => '2020',
                // Address Home
                'lat_house' => $faker->latitude(),
                'long_house' => $faker->longitude(),
                'address_house' => $faker->address(),
            ],
        ];

        foreach ($employees as $key => $value) {
            Employee::create($value);
        }
    }
}
