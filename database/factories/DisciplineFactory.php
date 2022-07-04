<?php

namespace Database\Factories;

use App\Faker\CodeFaker;
use App\Models\Discipline;
use App\Models\Media;
use App\Models\Professor;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisciplineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Discipline::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new CodeFaker($this->faker));

        return [
            'code' => $this->faker->code(),
            'name' => $this->faker->text(25),
            'synopsis' => $this->faker->text(),
            'difficulties' => $this->faker->text(50),
            'professor_id' => Professor::factory(),
        ];
    }

    /**
     * @param Professor $professor
     * @return DisciplineFactory
     */
    public function withProfessor(Professor $professor)
    {
        return $this->state(function (array $attributes) use ($professor) {
            return [
                'professor_id' => $professor->id,
            ];
        });
    }

    /**
     * @param int $count
     * @return DisciplineFactory
     */
    public function createMediaAfter($count = 1)
    {
        return $this->afterCreating(function (Discipline $discipline) use ($count) {
            Media::factory()
                ->count($count)
                ->withDiscipline($discipline)
                ->create();
        });
    }
}
