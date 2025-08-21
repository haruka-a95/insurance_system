<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InsuranceProduct;
use App\Enums\InsuranceType;
use App\Enums\ApprovalStatus;

class InsuranceProductFactory extends Factory
{
    protected $model = InsuranceProduct::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'type' => $this->faker->randomElement(InsuranceType::cases()),
            'approval_status' => $this->faker->randomElement(ApprovalStatus::cases()),
        ];
    }
}
