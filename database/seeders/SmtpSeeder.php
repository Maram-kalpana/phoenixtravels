<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SmtpModel;

class SmtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SmtpModel::create([
            'protocol' => 'SMTP',
            'smtp_host' => 'mail.ritps.com',
            'smtp_port' => '993',
            'smtp_user' => 'non-reply@ritps.com',
            'smtp_pass' => 'non-reply@ritps.com',
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    }
}
