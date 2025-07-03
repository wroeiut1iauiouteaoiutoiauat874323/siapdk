<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\area_user_aplikasi;

class areaseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['nik' => 51208806, 'area_user' => 'Cabang Pekalongan'],
            ['nik' => 101210072, 'area_user' => 'Cabang Pekalongan'],
        ];

        foreach ($areas as $area) {
            area_user_aplikasi::create($area);
        }
    }
}
