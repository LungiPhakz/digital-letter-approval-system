<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    // ✅ Force HTTPS in production (Render)
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }

    // ✅ Run migrations & seed (safe for Render free plan)
    if (app()->environment('production')) {
        try {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        } catch (\Exception $e) {
            // Prevent app crash if DB not ready yet
        }
    }

    // ✅ Create admin user if not exists
    try {
        if (!User::where('email', 'lungiphakz12@gmail.com')->exists()) {
            User::create([
                'name' => 'Bongiwe Phakathi',
                'email' => 'lungiphakz12@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'councilor', // ✅ correct role
            ]);
        }
    } catch (\Exception $e) {
        // Prevent crash if DB not ready yet
    }
}
}
