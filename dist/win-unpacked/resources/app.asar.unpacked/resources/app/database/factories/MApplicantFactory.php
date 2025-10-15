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
            "has_beard" => $this->faker->boolean(),
            'creed' => $this->faker->randomElement(['إسلام', 'سني']),
            'prays_with_jamaah' => $this->faker->boolean(),
            'scholars_you_follow' => $this->faker->randomElement(['الشيخ ابن باز', 'الشيخ العثيمين', 'الشيخ الألباني', 'الشيخ القرضاوي']),
            'medical_illness' => $this->faker->boolean(),
            'housing_situation' => $this->faker->randomElement(['independent', 'with_parents']),
            'accepts_working_wife' => $this->faker->boolean(),
            'preferred_cities_for_marriage' => $this->faker->optional()->words(3, true),
            'accepts_divorced_or_widowed' => $this->faker->boolean(),
            'maximum_accepted_age' => $this->faker->optional()->numberBetween(18, 60),
            'partner_requirements' => $this->faker->optional()->text(),
            'message_to_partner' => $this->faker->optional()->text(),
            'row_hash' => $this->faker->sha1(), // or any unique string
        ];
    }
}
