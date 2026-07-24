<?php

namespace Database\Factories;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'nis' => fake()->unique()->numerify('##########'),
            'nama' => fake()->name(),
            'rfid_uid' => fake()->unique()->bothify('??####??'),
            'class_id' => SchoolClass::factory(),
            'nama_ortu' => fake()->name(),
            'no_hp_ortu' => fake()->phoneNumber(),
            'foto' => null,
            'status' => 'aktif',
        ];
    }
}
