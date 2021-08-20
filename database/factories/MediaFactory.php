<?php

namespace Database\Factories;

use App\Enums\MediaType;
use App\Models\Discipline;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(25),
            'type' => MediaType::random(),
            'url' => $this->faker->text(50),
            'is_trailer' => $this->faker->boolean,
            'discipline_id' => Discipline::factory(),
        ];
    }

    /**
     * @param Discipline $discipline
     * @return MediaFactory
     */
    public function withDiscipline(Discipline $discipline)
    {
        return $this->state(function (array $attributes) use ($discipline) {
            return [
                'discipline_id' => $discipline->id,
            ];
        });
    }
}
