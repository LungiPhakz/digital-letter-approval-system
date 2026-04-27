@extends('layouts.public')

@section('title', 'Terms of Service')

@section('content')

<div class="gradient-primary text-white rounded-3xl p-10 shadow-lg mb-10">
    <h1 class="text-4xl font-bold">📜 Terms of Service</h1>
    <p class="mt-2 text-white/80">Rules and conditions for using this system</p>
</div>

<div class="space-y-6">

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">System Usage</h2>
        <p class="text-gray-600">
            This system is strictly for official proof of residence requests only.
        </p>
    </div>

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">User Responsibility</h2>
        <p class="text-gray-600">
            Users must provide accurate and truthful information.
            False information may lead to rejection or suspension.
        </p>
    </div>

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">System Integrity</h2>
        <p class="text-gray-600">
            Any misuse, hacking attempts, or fraud will be logged and blocked.
        </p>
    </div>

</div>

@endsection