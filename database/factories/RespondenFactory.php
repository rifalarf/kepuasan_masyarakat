<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Responden>
 */
class RespondenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gender' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'age' => fake()->numberBetween(15, 100),
            'education' => fake()->randomElement(['SD', 'SMP', 'SMA', 'D4', 'D3', 'S1', 'S2', 'S3']),
            'job' => fake()->randomElement(['Pelajar/Mahasiswa', 'PNS', 'TNI', 'Polisi', 'Swasta', 'Wirausaha', 'Lainnya']),
            'village' => fake()->randomElement(['Moodulio', 'Muara Bone', 'Masiaga', 'Taludaa', 'Permata', 'Inogaluma', 'Molamahu', 'Sogitia', 'Cendana Putih', 'Monano', 'Tumbuh Mekar', 'Waluhu', 'Ilohuuwa', 'Bilolantunga']),
            'domicile' => fake()->randomElement(['Garut', 'Luar Garut']),
            'email' => fake()->unique()->safeEmail(),
            'telp' => fake()->e164PhoneNumber() ,
        ];
    }
}
