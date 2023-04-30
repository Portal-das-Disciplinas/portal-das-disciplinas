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
            'public_email' => $this->faker->email(),
            'sigaa' => $this->faker->userName(),
            // 'instagram' => $this->faker->instagram(),
            // 'youtube' => $this->faker->youtube(),
            // 'facebook' => $this->faker->facebook(),
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
                    // 'rede_social1' => $user->rede_social1,
                    // 'link_rsocial1' => $user->link_rsocial1,
                    // 'rede_social2' => $user->rede_social2,
                    // 'link_rsocial2' => $user->link_rsocial2,
                    // 'rede_social3' => $user->rede_social3,
                    // 'link_rsocial3' => $user->link_rsocial3,
                    // 'rede_social4' => $user->rede_social4,
                    // 'link_rsocial4' => $user->link_rsocial4,
                    'user_id' => $user->id,
                ];
            }

            return [
                'user_id' => $user->id,
            ];
        });
    }
}
