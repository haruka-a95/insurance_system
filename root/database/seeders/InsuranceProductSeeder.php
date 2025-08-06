<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InsuranceProduct;
use App\Enums\ApprovalStatus;
use Faker\Factory as Faker;
use App\Enums\InsuranceType;

class InsuranceProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ja_JP');

        $types = InsuranceType::values();

        $statuses = array_map(fn($case) => $case->value, ApprovalStatus::cases());

        for ($i = 0; $i < 20; $i++) {
            InsuranceProduct::create([
                'name' => 'テストデータ',
                'description' => '保険テスト説明',
                'type' => $faker->randomElement($types),
                'approval_status' => $faker->randomElement($statuses),
            ]);
        }
    }
}
