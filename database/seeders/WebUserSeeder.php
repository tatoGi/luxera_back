<?php

namespace Database\Seeders;

use App\Models\WebUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class WebUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = 'Password123!';

        $users = [
            [
                'first_name' => 'Test',
                'surname' => 'User',
                'email' => 'test.user@momtabare.local',
            ],
            [
                'first_name' => 'tato',
                'surname' => 'laperashvili',
                'email' => 'tato.laperashvili95@gmail.com',
            ],
            [
                'first_name' => 'lika',
                'surname' => 'sitchinava',
                'email' => 'likasitchinava@gmail.com',
            ],
        ];

        foreach ($users as $u) {
            $user = WebUser::updateOrCreate(
                ['email' => $u['email']],
                [
                    'first_name' => $u['first_name'],
                    'surname' => $u['surname'] ?? null,
                    'password' => Hash::make($password),
                ]
            );

            try {
                if (isset($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            } catch (\Throwable $e) {
                // ignore
            }

            $this->command?->getOutput()->writeln("Seeded WebUser: {$u['email']} / {$password}");
        }
    }
}
