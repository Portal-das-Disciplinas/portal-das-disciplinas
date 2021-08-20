<?php

namespace Database\Factories;

use App\Models\Professor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Professor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'profile_pic_link' => $this->faker->imageUrl(),
            'public_email' => $this->faker->email,
            'user_id' => User::factory(),
        ];
    }

    /**
     * @param User $user
     * @param false $override
     * @return ProfessorFactory
     */
    public function withUser(User $user, $override = false)
    {
        return $this->state(function (array $attributes) use ($user, $override) {
            if ($override) {
                return [
                    'name' => $user->name,
                    'public_email' => $user->email,
                    'user_id' => $user->id,
                ];
            }

            return [
                'user_id' => $user->id,
            ];
        });
    }
}
