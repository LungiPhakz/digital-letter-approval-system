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
           
        } catch (\Exception $e) {
            // Prevent app crash if DB not ready yet
        }
    }

    // ✅ Create admin user if not exists
    try {
        // ✅ Councilor account
        if (!User::where('email', 'councilor@gmail.com')->exists()) {
            User::create([
                'name' => 'Bongiwe Phakathi',
                'email' => 'councilor@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'councilor',
            ]);
        }

        // ✅ Demo Admin account (SAFE DEMO)
        if (!User::where('email', 'admin-demo@communityletters.xyz')->exists()) {
            User::create([
                'name' => 'Demo Admin',
                'email' => 'admin-demo@communityletters.xyz',
                'password' => Hash::make('demo123'),
                'role' => 'demo', // 🔥 THIS is important
            ]);
        }

    } catch (\Exception $e) {
        // Prevent crash if DB not ready yet
    }
}
}
