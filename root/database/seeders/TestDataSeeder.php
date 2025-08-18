<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Customers
        DB::table('customers')->insert([
            [
                'name' => '田中 太郎',
                'birthday' => '1985-04-12',
                'phone' => '0312345678',
                'cellphone' => '09012345678',
                'email' => 'tanaka@example.com',
                'address' => '東京都新宿区1-2-3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '佐藤 花子',
                'birthday' => '1990-07-25',
                'phone' => '0459876543',
                'cellphone' => '08087654321',
                'email' => 'sato@example.com',
                'address' => '横浜市中区4-5-6',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insurance Products
        DB::table('insurance_products')->insert([
            [
                'id' => 1,
                'name' => 'がん保険',
                'description' => 'がん保険をカバー',
                'type' => 'cancer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => '生命保険',
                'description' => '死亡保障と医療保障を提供',
                'type' => 'life',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insurance Policies
        DB::table('insurance_policies')->insert([
            [
                'policy_number' => 'TEST-10001',
                'customer_id' => 1,
                'product_id' => 1,
                'start_date' => '2024-01-01',
                'end_date' => '2025-01-01',
                'premium_amount' => 50000.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'policy_number' => 'POL-10002',
                'customer_id' => 2,
                'product_id' => 2,
                'start_date' => '2023-06-01',
                'end_date' => '2026-06-01',
                'premium_amount' => 120000.00,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
