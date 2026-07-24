<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    protected $model = SchoolClass::class;

    public function definition(): array
    {
        return [
            'nama_kelas' => fake()->randomElement(['VII-A', 'VII-B', 'VII-C', 'VIII-A', 'VIII-B', 'IX-A', 'IX-B']),
            'wali_kelas_id' => null,
            'academic_year_id' => AcademicYear::factory(),
        ];
    }
}
