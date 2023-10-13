<?php

namespace Database\Factories;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worktime>
 */
class WorktimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date = Carbon::instance($this->faker->dateTimeBetween('-1 months','+1 months'));
        // add 2 hours to start
        $end_date = (clone $start_date)->addHours(2);
        #dd($start_date, $end_date);

        $end = $this->faker->optional(0.9, null)->dateTimeBetween($start_date, $end_date);

        // get duration
        $duration = $end ? CarbonInterval::make($start_date->diff($end))->totalSeconds : null;

        return [
            'start' => $start_date,
            'end' => $end,
            'duration' => $duration,
        ];
    }
}
