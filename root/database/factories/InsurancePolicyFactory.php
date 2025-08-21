<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InsurancePolicy;
use App\Enums\InsuranceType;
use App\Enums\ApprovalStatus;
use App\Enums\PolicyStatus;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class InsurancePolicyFactory extends Factory
{
    protected $model = InsurancePolicy::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('-1year', 'now');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2years');

        return [
            'policy_number' => 'POL-' . $this->faker->unique()->numerify(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'premium_amount' => $this->faker->numberBetween(1000, 100000),
            'status' => $this->faker->randomElement(PolicyStatus::cases()),
        ];
    }
}
