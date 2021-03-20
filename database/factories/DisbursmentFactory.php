<?php

namespace Database\Factories;

use App\Models\Disbursment;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisbursmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Disbursment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomDigit,
            'transaction_id' => $this->faker->unique()->randomDigit,
            'bank_code' => $this->faker->word,
            'account_number' => $this->faker->randomDigit,
            'beneficiary_name' => $this->faker->name,
            'remark' => $this->faker->word,
            'fee' => 4000,
            'timestamp' => now(),
            'status' => 'success'
        ];
    }
}
