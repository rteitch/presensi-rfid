<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    protected $model = AcademicYear::class;

    public function definition(): array
    {
        $year = fake()->year();

        return [
            'nama' => ($year - 1).'/'.$year,
            'tanggal_mulai' => ($year - 1).'-07-01',
            'tanggal_selesai' => $year.'-06-30',
            'is_active' => false,
        ];
    }
}
