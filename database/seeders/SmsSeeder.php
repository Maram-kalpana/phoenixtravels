<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SMSModel;

class SmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SMSModel::create([
            'sms_url' => 'http://sms.messageindia.in/v2/sendSMS',
            'api_key' => 'fdc700f6-9e8f-458f-aa62-912e5b05ba56',
            'sender_id' => 'KMSAPP',
            'username' => 'SDNSoftech',
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    }
}
