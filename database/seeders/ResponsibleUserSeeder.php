<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ResponsibleUserSeeder extends Seeder
{
    public function run(): void
    {
        $credentials = $this->credentials();

        if ($credentials === null) {
            $this->command?->warn('حساب مسئول ساخته نشد. ROSHDINO_ADMIN_EMAIL و یک ROSHDINO_ADMIN_PASSWORD حداقل ۱۲ نویسه‌ای را در env بگذار.');

            return;
        }

        $user = User::query()->where('email', $credentials['email'])->first();

        if ($user === null) {
            User::query()->create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => $credentials['password'],
                'email_verified_at' => now(),
            ]);

            return;
        }

        $user->update([
            'name' => $credentials['name'],
        ]);
    }

    /**
     * @return array{name: string, email: string, password: string}|null
     */
    private function credentials(): ?array
    {
        $email = config('roshdino.admin.email');
        $password = config('roshdino.admin.password');
        $name = config('roshdino.admin.name');

        if (! is_string($email) || ! is_string($password)) {
            return null;
        }

        $email = Str::lower(trim($email));

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false || strlen($password) < 12) {
            return null;
        }

        return [
            'name' => is_string($name) && trim($name) !== '' ? trim($name) : 'مسئول رشدینو',
            'email' => $email,
            'password' => $password,
        ];
    }
}
