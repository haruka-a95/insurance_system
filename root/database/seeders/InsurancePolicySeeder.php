<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Enums\PolicyStatus;

class InsurancePolicySeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ja_JP');

        // 既存の顧客IDと保険製品IDを取得
        $customerIds = DB::table('customers')->pluck('id')->toArray();
        $productIds = DB::table('insurance_products')->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++) {
            $startDate = $faker->dateTimeBetween('-2 years', 'now');
            $endDate = $faker->dateTimeBetween($startDate, '+2 years');

            $policyId = DB::table('insurance_policies')->insertGetId([
                'policy_number' => 'POL-' . $faker->unique()->numerify('#####'),
                'customer_id' => $faker->randomElement($customerIds),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'premium_amount' => $faker->randomFloat(2, 10000, 200000),
                'status' => $faker->randomElement(PolicyStatus::values()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 保険製品との多対多中間テーブル (insurance_policy_product)
            $maxProducts = min(3, count($productIds));
            $assignedProducts = $faker->randomElements($productIds, rand(1, $maxProducts));

            DB::table('insurance_policy_product')->insert(
                array_map(fn($pid) => [
                    'insurance_policy_id' => $policyId,
                    'insurance_product_id' => $pid,
                ], $assignedProducts)
            );
        }
    }
}
