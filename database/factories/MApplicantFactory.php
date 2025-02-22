<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\mApplicant>
 */
class MApplicantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name('male'),
            'city' => $this->faker->city(),
            'marital_status' => $this->faker->randomElement(['عازب', 'أرمل', 'مطلق',"متزوج"]),
            'age' => $this->faker->numberBetween(18, 60),
            'phone' => $this->faker->phoneNumber(),
            'job' => $this->faker->jobTitle(),
            'application_date' => $this->faker->date(),
            'document_status' => true,
        ];
    }
}
