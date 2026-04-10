<?php

namespace Database\Factories;

use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\Factory;

class MachineFactory extends Factory
{
    protected $model = Machine::class;

    public function definition(): array
    {
        $number = $this->faker->unique()->numberBetween(1, 99);
        
        return [
            'machine_name' => 'PisoNet Station ' . $number,
            'machine_code' => 'PC-' . str_pad($number, 2, '0', STR_PAD_LEFT),
            'ip_address' => $this->faker->localIpv4(),
            'location' => $this->faker->randomElement([
                'Main Floor - Left Side',
                'Main Floor - Center',
                'Main Floor - Right Side',
                'Second Floor - Gaming Area',
                'Second Floor - Study Area'
            ]),
            'status' => $this->faker->randomElement(['online', 'offline', 'maintenance']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'online',
        ]);
    }

    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'offline',
        ]);
    }
}
