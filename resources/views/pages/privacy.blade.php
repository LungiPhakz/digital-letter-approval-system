@extends('layouts.public')

@section('title', 'Privacy Policy')

@section('content')

<div class="gradient-primary text-white rounded-3xl p-10 shadow-lg mb-10">
    <h1 class="text-4xl font-bold">📜 Privacy Policy</h1>
    <p class="mt-2 text-white/80">How your personal information is collected and used</p>
</div>

<div class="space-y-6">

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">Data Collection</h2>
        <p class="text-gray-600">
            We collect only necessary information such as name, ID number,
            and address for proof of residence verification.
        </p>
    </div>

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">Purpose of Use</h2>
        <p class="text-gray-600">
            Data is used strictly for generating official community letters.
        </p>
    </div>

    <div class="card p-6">
        <h2 class="text-xl font-bold gradient-text mb-2">No Third-Party Sharing</h2>
        <p class="text-gray-600">
            We do not sell, share, or expose your personal data to external parties.
        </p>
    </div>

</div>

@endsection