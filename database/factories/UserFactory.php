<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Kitloom\Permissions\Contracts\UserRoles;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }

    /**
     * A user is an administrator unless told otherwise: tests and the seeder
     * work with the whole admin. {@see withRole()} runs later and replaces it.
     */
    public function configure(): static
    {
        return $this->afterCreating(fn (User $user) => app(UserRoles::class)->setRole($user, 'administrator'));
    }

    /**
     * A user with this role; NULL — without a role on the site.
     */
    public function withRole(?string $role): static
    {
        return $this->afterCreating(fn (User $user) => app(UserRoles::class)->setRole($user, $role));
    }
}
