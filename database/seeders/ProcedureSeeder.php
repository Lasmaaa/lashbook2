<?php

namespace Database\Seeders;

use App\Models\Procedure;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    public function run(): void
    {
        Procedure::updateOrCreate(
            ['name_lv' => 'Klasika'],
            [
                'name_en' => 'Classic',
                'name_ru' => 'Классика',
                'duration' => 120,
                'price' => 45.00,
            ]
        );

        Procedure::updateOrCreate(
            ['name_lv' => 'Apjoms'],
            [
                'name_en' => 'Volume',
                'name_ru' => 'Объем',
                'duration' => 150,
                'price' => 55.00,
            ]
        );

        Procedure::updateOrCreate(
            ['name_lv' => 'Skropstu noņemšana'],
            [
                'name_en' => 'Lash Removal',
                'name_ru' => 'Снятие ресниц',
                'duration' => 45,
                'price' => 15.00,
            ]
        );
    }
}
