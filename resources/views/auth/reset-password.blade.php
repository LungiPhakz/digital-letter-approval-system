<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Reset Password - CommunityLetters</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body {
    font-family: 'Inter', sans-serif;
}

.gradient-primary {
    background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
}

.card-modern {
    background:white;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
    transition:0.3s;
}

.card-modern:hover {
    transform:translateY(-4px);
    box-shadow:0 15px 40px rgba(0,0,0,.12);
}

.input-field {
    width:100%;
    padding:12px;
    border:2px solid #e5e7eb;
    border-radius:10px;
    outline:none;
    transition:0.2s;
}

.input-field:focus {
    border-color:#7c3aed;
}
</style>

</head>

<body class="bg-gradient-to-br from-purple-50 via-blue-50 to-purple-100 min-h-screen flex items-center justify-center px-6">

<div class="max-w-md w-full">

<div class="card-modern p-8">

<!-- HEADER -->
<div class="text-center mb-6">

<div class="text-5xl mb-4 inline-block p-4 bg-gradient-to-br from-purple-100 to-blue-100 rounded-2xl">
🔐
</div>

<h1 class="text-2xl font-bold text-gray-800">
Reset Your Password
</h1>

<p class="text-gray-500 text-sm mt-1">
Create a new secure password for your account
</p>

</div>

<!-- ERRORS -->
@if ($errors->any())
<div class="mb-4 p-3 bg-red-100 text-red-700 rounded text-sm">
    {{ $errors->first() }}
</div>
@endif

<!-- SUCCESS -->
@if (session('status'))
<div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
    {{ session('status') }}
</div>
@endif

<!-- FORM -->
<form method="POST" action="{{ route('password.update') }}">
@csrf

<input type="hidden" name="token" value="{{ request()->route('token') }}">

<input type="hidden" name="email" value="{{ request('email') }}">

<!-- PASSWORD -->
<div class="mb-4">
<label class="text-sm text-gray-600">New Password</label>
<input type="password" name="password" required class="input-field mt-1">
</div>

<!-- CONFIRM -->
<div class="mb-6">
<label class="text-sm text-gray-600">Confirm Password</label>
<input type="password" name="password_confirmation" required class="input-field mt-1">
</div>

<button type="submit"
class="w-full py-3 gradient-primary text-white rounded-lg font-bold">
Update Password
</button>

</form>

<!-- BACK -->
<div class="text-center mt-6">
<a href="{{ route('councilor.login') }}" class="text-purple-600 font-semibold hover:text-purple-700">
← Back to Login
</a>
</div>

</div>

</div>

</body>
</html>