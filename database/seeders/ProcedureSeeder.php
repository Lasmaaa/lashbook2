<?php

namespace Database\Seeders;

use App\Models\Procedure;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    public function run(): void
    {
        Procedure::updateOrCreate(
            ['code' => 'classic'],
            [
                'name_lv' => 'Klasika',
                'name_en' => 'Classic',
                'name_ru' => 'Классика',
                'duration' => 120,
                'price' => 15.00,
                'code' => 'classic',
            ]
        );

        Procedure::updateOrCreate(
            ['code' => 'volume'],
            [
                'name_lv' => 'Apjoms',
                'name_en' => 'Volume',
                'name_ru' => 'Объем',
                'duration' => 150,
                'price' => 0.00,
                'code' => 'volume',
            ]
        );

        Procedure::updateOrCreate(
            ['code' => 'volume_2d_3d'],
            [
                'name_lv' => 'Apjoms 2D-3D',
                'name_en' => 'Volume 2D-3D',
                'name_ru' => 'Объем 2D-3D',
                'duration' => 150,
                'price' => 20.00,
                'code' => 'volume_2d_3d',
            ]
        );

        Procedure::updateOrCreate(
            ['code' => 'volume_4d_plus'],
            [
                'name_lv' => 'Apjoms 4D+',
                'name_en' => 'Volume 4D+',
                'name_ru' => 'Объем 4D+',
                'duration' => 150,
                'price' => 25.00,
                'code' => 'volume_4d_plus',
            ]
        );

        Procedure::updateOrCreate(
            ['code' => 'removal_other_master'],
            [
                'name_lv' => 'Skropstu noņemšana no cita meistara',
                'name_en' => 'Lash Removal from another artist',
                'name_ru' => 'Снятие ресниц у другого мастера',
                'duration' => 45,
                'price' => 5.00,
                'code' => 'removal_other_master',
            ]
        );
    }
}
