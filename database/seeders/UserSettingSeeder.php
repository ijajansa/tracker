<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;

class UserSettingSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->get()->each(function (User $user) {
            UserSetting::query()->firstOrCreate(
                ['user_id' => $user->id],
                ['currency' => 'INR', 'currency_symbol' => '₹', 'theme' => 'light', 'auto_lock_minutes' => 5]
            );
        });
    }
}
