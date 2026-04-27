<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CommunityLetters - Resident Login</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
* { box-sizing:border-box; }

body {
    font-family: 'Inter', sans-serif;
}

.gradient-primary {
    background: linear-gradient(135deg,#667eea 0%,#764ba2 100%);
}
.card-modern{ 
    background:white; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,.08); transition:0.3s; 
} 
.card-modern:hover{ 
    transform:translateY(-4px); box-shadow:0 15px 40px rgba(0,0,0,.12); 
}


.otp-input {
    width: 45px;
    height: 50px;
    text-align: center;
    font-size: 20px;
    border: 2px solid #ddd;
    border-radius: 8px;
}

.otp-input:focus {
    border-color: #7c3aed;
    outline: none;
}

/* 🔥 PROFESSIONAL ERROR STYLE */
.error-text {
    font-size: 12px;
    color: #dc2626;
    margin-top: 4px;
}
</style>
</head>

<body class="bg-gradient-to-br from-purple-50 via-blue-50 to-purple-100 min-h-screen flex items-center justify-center px-6">

<<div class="max-w-md w-full"> 
    <div class="card-modern p-8"> <!-- Header --> 
        <div class="text-center mb-8"> 
    <div class="text-6xl mb-4 inline-block p-4 bg-gradient-to-br from-purple-100 to-blue-100 rounded-2xl"> 👤 </div>


<h1 class="text-3xl font-bold text-center mb-6">Resident Login</h1>

<!-- GLOBAL ERROR -->
@if(session('error'))
<div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
    {{ session('error') }}
</div>
@endif

<form id="loginForm">

@csrf

<!-- NAME -->
<div class="mb-3">
<input type="text" name="name" id="name"
placeholder="Full Name"
class="w-full p-3 border rounded focus:outline-none">

<p id="name_error" class="error-text hidden"></p>
</div>

<!-- EMAIL -->
<div class="mb-3">
<input type="email" name="email" id="email"
placeholder="Email"
class="w-full p-3 border rounded focus:outline-none">

<p id="email_error" class="error-text hidden"></p>
</div>

<!-- PHONE -->
<div class="mb-3">
<input type="tel" name="phone" id="phone"
placeholder="+27821234567 or 0821234567"
class="w-full p-3 border rounded focus:outline-none">

<p id="phone_error" class="error-text hidden"></p>
</div>

<button type="submit"
class="w-full py-3 gradient-primary text-white rounded-lg font-bold">
Login to Dashboard
</button>

</form>

</div>


<!-- OTP MODAL -->
<div id="otpModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

<div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-sm text-center">

<h2 class="text-2xl font-bold mb-2">Verify OTP</h2>
<p class="text-gray-500 mb-6">Enter the 6-digit code sent to your email</p>

<div class="flex justify-center gap-2 mb-4">
<input maxlength="1" class="otp-input" />
<input maxlength="1" class="otp-input" />
<input maxlength="1" class="otp-input" />
<input maxlength="1" class="otp-input" />
<input maxlength="1" class="otp-input" />
<input maxlength="1" class="otp-input" />
</div>

<p id="otpError" class="error-text hidden mb-3"></p>

<button onclick="submitOTP()"
class="w-full py-3 bg-purple-600 text-white rounded-lg font-bold mb-3">
Verify OTP
</button>

<button id="resendBtn" onclick="resendOTP()"
class="text-sm text-purple-600 font-semibold hidden">
Resend OTP
</button>

<p id="timerText" class="text-sm text-gray-400"></p>

</div>
</div>

<script>

// ================= INPUTS =================
const inputs = document.querySelectorAll(".otp-input");

// OTP move
inputs.forEach((input, index) => {
    input.addEventListener("input", () => {
        if (input.value && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !input.value && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

// ================= LOGIN =================
document.getElementById("loginForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    clearErrors();

    const formData = new FormData(this);

    const res = await fetch("{{ route('resident.login.post') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: formData
    });

    const data = await res.json();

    if (!res.ok) {

        // show backend validation errors properly
        if (data.errors) {
            showFieldErrors(data.errors);
        } else {
            alert(data.message || "Login failed");
        }

        return;
    }

    openOtpModal();
});

// ================= SHOW FIELD ERRORS =================
function showFieldErrors(errors) {

    if (errors.name) showError("name_error", errors.name[0]);
    if (errors.email) showError("email_error", errors.email[0]);
    if (errors.phone) showError("phone_error", errors.phone[0]);
}

function showError(id, msg) {
    const el = document.getElementById(id);
    el.textContent = msg;
    el.classList.remove("hidden");
}

// clear errors
function clearErrors() {
    ["name_error","email_error","phone_error","otpError"].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add("hidden");
    });
}

// ================= OTP =================
function openOtpModal() {
    document.getElementById("otpModal").classList.remove("hidden");
    startTimer();
    inputs[0].focus();
}

function submitOTP() {

    let otp = "";
    inputs.forEach(i => otp += i.value);

    if (otp.length !== 6) {
        showOTPError("Enter complete OTP");
        return;
    }

    fetch("{{ route('otp.verify') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({ otp })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            showOTPError(data.message);
        }
    });
}

function showOTPError(msg) {
    const el = document.getElementById("otpError");
    el.textContent = msg;
    el.classList.remove("hidden");
}

// ================= TIMER =================
let timeLeft = 30;
let interval;

function startTimer() {
    const timer = document.getElementById("timerText");
    const resendBtn = document.getElementById("resendBtn");

    resendBtn.classList.add("hidden");
    timeLeft = 30;

    clearInterval(interval);

    interval = setInterval(() => {
        timeLeft--;
        timer.textContent = `Resend in ${timeLeft}s`;

        if (timeLeft <= 0) {
            clearInterval(interval);
            timer.textContent = "";
            resendBtn.classList.remove("hidden");
        }
    }, 1000);
}

// ================= RESEND =================
function resendOTP() {
    fetch("/resend-otp", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    });

    startTimer();
}

</script>

</body>
</html>