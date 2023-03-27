<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prov = [
            ['id'=>1,'nama'=>'ACEH','kode_area'=>'11'],
            ['id'=>6728,'nama'=>'SUMATERA UTARA','kode_area'=>'12'],
            ['id'=>12920,'nama'=>'SUMATERA BARAT','kode_area'=>'13'],
            ['id'=>14086,'nama'=>'RIAU','kode_area'=>'14'],
            ['id'=>15885,'nama'=>'JAMBI','kode_area'=>'15'],
            ['id'=>17404,'nama'=>'SUMATERA SELATAN','kode_area'=>'16'],
            ['id'=>20802,'nama'=>'BENGKULU','kode_area'=>'17'],
            ['id'=>22328,'nama'=>'LAMPUNG','kode_area'=>'18'],
            ['id'=>24993,'nama'=>'KEPULAUAN BANGKA BELITUNG','kode_area'=>'19'],
            ['id'=>25405,'nama'=>'KEPULAUAN RIAU','kode_area'=>'21'],
            ['id'=>25823,'nama'=>'DKI JAKARTA','kode_area'=>'31'],
            ['id'=>26141,'nama'=>'JAWA BARAT','kode_area'=>'32'],
            ['id'=>32676,'nama'=>'JAWA TENGAH','kode_area'=>'33'],
            ['id'=>41863,'nama'=>'DAERAH ISTIMEWA YOGYAKARTA','kode_area'=>'34'],
            ['id'=>42385,'nama'=>'JAWA TIMUR','kode_area'=>'35'],
            ['id'=>51578,'nama'=>'BANTEN','kode_area'=>'36'],
            ['id'=>53241,'nama'=>'BALI','kode_area'=>'51'],
            ['id'=>54020,'nama'=>'NUSA TENGGARA BARAT','kode_area'=>'52'],
            ['id'=>55065,'nama'=>'NUSA TENGGARA TIMUR','kode_area'=>'53'],
            ['id'=>58285,'nama'=>'KALIMANTAN BARAT','kode_area'=>'61'],
            ['id'=>60371,'nama'=>'KALIMANTAN TENGAH','kode_area'=>'62'],
            ['id'=>61965,'nama'=>'KALIMANTAN SELATAN','kode_area'=>'63'],
            ['id'=>64111,'nama'=>'KALIMANTAN TIMUR','kode_area'=>'64'],
            ['id'=>65702,'nama'=>'SULAWESI UTARA','kode_area'=>'71'],
            ['id'=>67393,'nama'=>'SULAWESI TENGAH','kode_area'=>'72'],
            ['id'=>69268,'nama'=>'SULAWESI SELATAN','kode_area'=>'73'],
            ['id'=>72551,'nama'=>'SULAWESI TENGGARA','kode_area'=>'74'],
            ['id'=>74716,'nama'=>'GORONTALO','kode_area'=>'75'],
            ['id'=>75425,'nama'=>'SULAWESI BARAT','kode_area'=>'76'],
            ['id'=>76096,'nama'=>'MALUKU','kode_area'=>'81'],
            ['id'=>77085,'nama'=>'MALUKU UTARA','kode_area'=>'82'],
            ['id'=>78203,'nama'=>'PAPUA','kode_area'=>'91'],
            ['id'=>81877,'nama'=>'PAPUA BARAT','kode_area'=>'92'],
            ['id'=>928068,'nama'=>'KALIMANTAN UTARA','kode_area'=>'65'],
        ];

        foreach ($prov as $key => $value) {
            Province::create($value);
        }
    }
}
