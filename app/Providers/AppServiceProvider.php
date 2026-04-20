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
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }

    // Auto-create admin (runs every deployment safely)
    if (User::where('email', 'admin@system.com')->doesntExist()) {
        User::create([
            
            'name' => 'Bongiwe Phakathi',
            'email' => 'lungiphakz12@gmail.com',
            'password' => Hash::make('Bongi@1997'),
            'role' => 'councilor', // if your system has roles
        ]);
    }
    }
}
