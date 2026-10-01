<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SettingModel;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingModel::create([
            'logo' => 'uploads/dummy/circle.png',
            'fav' => 'uploads/dummy/circle.png',
            'address' => 'Hyderabad',
            'about' => 'Dashboard Plus Working on CRM',
            'phone' => 9966768227,
            'email' => 'non-repaly@dashboardplus.in',
            'company_name' => 'Dashboard Plus',
            'color' => '#0000',
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    }
}
