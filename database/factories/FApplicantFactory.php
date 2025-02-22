<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\fApplicant>
 */
class FApplicantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name('female'),
            'city' => $this->faker->city(),
            'marital_status' => $this->faker->randomElement(['عازبة', 'أرملة', 'مطلقة']),
            'age' => $this->faker->numberBetween(18, 60),
            'phone' => $this->faker->phoneNumber(),
            'activity_status' => $this->faker->randomElement(['تدرس', 'قارة', 'تعمل']),
            'application_date' => $this->faker->date(),
            'document_status' => true,
        ];
    }
}
