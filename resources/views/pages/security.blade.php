@extends('layouts.public')

@section('title', 'Security Policy')

@section('content')

<!-- HEADER -->
<div class="gradient-primary text-white rounded-3xl p-10 shadow-lg mb-10">
    <h1 class="text-4xl font-bold">🔐 Security & Data Protection</h1>
    <p class="mt-2 text-white/80">How we protect your identity, data, and system access</p>
</div>

<!-- GRID -->
<div class="grid md:grid-cols-2 gap-6">

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">Encrypted Data</h2>
        <p class="text-gray-600">
            All sensitive information is protected using strong encryption.
            Your data cannot be accessed without authorization.
        </p>
    </div>

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">ID Usage Policy</h2>
        <p class="text-gray-600">
            Your South African ID is used strictly for verification.
            It is never publicly displayed or shared.
        </p>
    </div>

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">System Protection</h2>
        <p class="text-gray-600">
            Laravel authentication, CSRF protection, and session security
            protect all user actions.
        </p>
    </div>

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">Audit Control</h2>
        <p class="text-gray-600">
            Every request is logged for accountability and transparency.
        </p>
    </div>

</div>

@endsection