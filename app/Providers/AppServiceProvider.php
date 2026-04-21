<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
         // Force HTTPS (you already added this)
   if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }


         // Create admin user
    if (!User::where('email', 'lungiphakz12@gmail.com')->exists()) {
        User::create([
            'name' => 'Bongiwe Phakathi',
            'email' => 'lungiphakz12@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin' // ✅ FIXED
        ]);
    }

    
    }
}
