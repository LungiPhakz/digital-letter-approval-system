<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>CommunityLetters - Councilor Login</title>

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com/3.4.17"></script>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<!-- PDF Generator -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- SDK -->
<script src="/_sdk/element_sdk.js"></script>
<script src="/_sdk/data_sdk.js"></script>

<style>

*{
box-sizing:border-box;
}

body{
font-family:'Inter',sans-serif;
}

.gradient-secondary{
background:linear-gradient(135deg,#f093fb 0%,#f5576c 100%);
}

.card-modern{
background:white;
border-radius:20px;
box-shadow:0 10px 30px rgba(0,0,0,.08);
transition:0.3s;
}

.card-modern:hover{
transform:translateY(-4px);
box-shadow:0 15px 40px rgba(0,0,0,.12);
}

</style>

</head>

<body class="bg-gradient-to-br from-purple-50 via-pink-50 to-purple-100 min-h-screen flex items-center justify-center px-6">

<div class="max-w-md w-full">

<div class="card-modern p-8">

<!-- Header -->

<div class="text-center mb-8">

<div class="text-6xl mb-4 inline-block p-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl">
👨‍⚖️
</div>

<h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
Councilor Login
</h1>

<p class="text-gray-600 mt-2">
Administrative access to manage requests
</p>

</div>

<!-- Login Form -->

<form method="POST" action="{{ route('councilor.login.post') }}">

@csrf

<input
type="email"
name="email"
placeholder="Councilor Email"
required
class="w-full mb-4 p-3 border rounded-lg focus:outline-none focus:border-purple-500"
/>

<input
type="password"
name="password"
placeholder="Password"
required
class="w-full mb-6 p-3 border rounded-lg focus:outline-none focus:border-purple-500"
/>

<button
type="submit"
class="w-full py-3 gradient-secondary text-white rounded-lg font-bold hover:shadow-lg transition"
>

Login to Dashboard

</button>

</form>

<!-- Back -->

<div class="text-center mt-6">

<a href="{{ route('role') }}" class="text-purple-600 font-semibold hover:text-purple-700">
← Back to Role Selection
</a>

</div>

@if(session('error'))
<script>
    showToast("{{ session('error') }}", "error");
</script>
@endif

</div>

</div>
<script>
<script>
function showToast(message, type = 'success') {
    const toast = document.createElement('div');

    toast.className = `notification-toast toast-${type}`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = '0.4s ease';
    }, 2500);

    setTimeout(() => toast.remove(), 3000);
}
</script>
</script>
</body>
</html>