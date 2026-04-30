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

       /* prevents it from becoming too tall */
 
}

.card-modern:hover{
transform:translateY(-4px);
box-shadow:0 15px 40px rgba(0,0,0,.12);
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-in {
    animation: scaleIn 0.3s ease;
}

</style>

</head>

<body class="bg-gradient-to-br from-purple-50 via-pink-50 to-purple-100 min-h-screen flex items-center justify-center px-6">

<div class="max-w-md w-full">

<div class="card-modern p-6">

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

@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('councilor.login.post') }}">

@csrf

<input
type="email"
name="email"
placeholder="Councilor Email"
required
class="w-full mb-3 p-3 border rounded-lg focus:outline-none focus:border-purple-500"
/>

<input
type="password"
name="password"
placeholder="Password"
required
class="w-full mb-4 p-3 border rounded-lg focus:outline-none focus:border-purple-500"
/>

<!--DEMO BOX (CLEAN UI) -->
<div class="text-sm bg-blue-50 border border-blue-200 text-blue-700 p-2 rounded-lg mb-3">
    <strong>Demo Access:</strong><br>
    Email: admin-demo@communityletters.xyz <br>
    Password: demo123
</div>

<div class="text-right mb-4">
    <button type="button"
        onclick="openForgotModal()"
        class="text-sm text-purple-600 font-semibold hover:underline">
        Forgot Password?
    </button>
</div>

<button
type="submit"
class="w-full py-3 gradient-secondary text-white rounded-lg font-bold hover:shadow-lg transition"
>

Login to Dashboard

</button>

</form>

<!-- FORGOT PASSWORD MODAL -->
<!-- FORGOT PASSWORD MODAL -->
<div id="forgotModal"
     class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 animate-scale-in">

        <h2 class="text-2xl font-bold text-center mb-2">
            Forgot Password
        </h2>

        <p class="text-gray-500 text-center mb-6 text-sm">
            Enter your email and we’ll send you a secure reset link
        </p>

        <!-- FORM (IMPORTANT FIX) -->
        <form id="forgotForm" onsubmit="event.preventDefault(); sendResetLink();">

            <input type="email"
                   id="resetEmail"
                   required
                   placeholder="Enter your email"
                   class="w-full p-3 border rounded-lg mb-3 focus:outline-none focus:border-purple-500"/>

            <p id="resetMsg" class="text-sm mb-3 hidden text-center"></p>

            <button type="submit"
                    id="resetBtn"
                    class="w-full py-3 gradient-secondary text-white rounded-lg font-bold transition">
                Send Reset Link
            </button>

        </form>

        <button onclick="closeForgotModal()"
                class="w-full mt-3 py-2 border rounded-lg hover:bg-gray-50">
            Cancel
        </button>

    </div>
</div>

<!-- Back -->

<div class="text-center mt-6">

<a href="{{ route('role') }}" class="text-purple-600 font-semibold hover:text-purple-700">
← Back to Role Selection
</a>

</div>

</div>

</div>




<script>
function openForgotModal() {
    document.getElementById('forgotModal').classList.remove('hidden');
}

function closeForgotModal() {
    document.getElementById('forgotModal').classList.add('hidden');
}

function sendResetLink() {
    const email = document.getElementById('resetEmail').value;
    const msg = document.getElementById('resetMsg');
    const btn = document.getElementById('resetBtn');

    msg.classList.remove("hidden");
    msg.textContent = "Sending...";
    msg.className = "text-gray-500 text-sm mb-3 text-center";

    btn.disabled = true;

    fetch("{{ route('password.email') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            "Accept": "application/json"
        },
        body: JSON.stringify({ email })
    })
    .then(async res => {
        const data = await res.json();

        btn.disabled = false;

        if (res.ok && data.success) {
            msg.className = "text-green-600 text-sm mb-3 text-center";
            msg.textContent = "Reset link sent! Check your email.";
        } else {
            msg.className = "text-red-600 text-sm mb-3 text-center";
            msg.textContent = data.message || "Failed to send reset link.";
        }
    })
    .catch(() => {
        btn.disabled = false;
        msg.className = "text-red-600 text-sm mb-3 text-center";
        msg.textContent = "Network error. Try again.";
    });
}

function resetForgotForm() {
    document.getElementById('resetEmail').value = "";
    document.getElementById('resetMsg').classList.add("hidden");
}

</script>
</body>
</html>