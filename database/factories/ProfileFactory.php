<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $users = collect(User::all()->modelKeys());
        return [
            'bio' => fake()->text(500),
            'phone_number' => fake()->phoneNumber,
            'address' => fake()->address,
            'user_id' => $users->random(),
            'gender' => fake()->randomElement(['male', 'female'])
        ];
    }
}
