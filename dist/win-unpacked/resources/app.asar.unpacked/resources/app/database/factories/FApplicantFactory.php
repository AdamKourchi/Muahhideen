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
            "accepts_divorced_or_widowed" => $this->faker->boolean(),
            "accepts_living_with_inlaws" => $this->faker->boolean(),
            "maximum_accepted_age" => $this->faker->numberBetween(18, 60),
            "accepts_polygamy" => $this->faker->boolean(),
            'prays_on_time' => $this->faker->boolean(),
            'height' => $this->faker->randomFloat(2, 1.5, 2.0), // Height in meters
            'hijab_type' => $this->faker->randomElement(['نقاب', 'خمار', 'حجاب', 'لا ترتدي حجاب']),
            'weight' => $this->faker->randomFloat(2, 40, 100), // Weight in kg  
            'row_hash' => $this->faker->sha1(), // or any unique string
        ];
    }
}
