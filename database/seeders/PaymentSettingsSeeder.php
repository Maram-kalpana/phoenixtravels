<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentModel;

class PaymentSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentModel::create([
            'razorpay_key_live' => 'rzp_live_bYDpol4mXhSJS1',
            'razorpay_key_secret_live' => 'ooIGsdDQagRSvtJpVnFngIvO',
            'razorpay_key_test' => 'rzp_test_wvWkUr5VGDz70A',
            'razorpay_key_secret_test' => '0CTEh4O4BjjklO2F4oexUR01',
            'mode_type' => 'test_mode',
            'color' => '#1d732c',
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    }
}
