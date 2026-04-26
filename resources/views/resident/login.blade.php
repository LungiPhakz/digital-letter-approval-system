<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CommunityLetters - Resident Login</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
*{ 
    box-sizing:border-box; 
} 
body{
     font-family:'Inter',sans-serif; 
    } 
.gradient-primary{
     background:linear-gradient(135deg,#667eea 0%,#764ba2 100%); 
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

</style>
</head>

<body class="bg-gradient-to-br from-purple-50 via-blue-50 to-purple-100 min-h-screen flex items-center justify-center px-6">

<div class="max-w-md w-full"> 
    <div class="card-modern p-8"> <!-- Header --> 
        <div class="text-center mb-8"> 
    <div class="text-6xl mb-4 inline-block p-4 bg-gradient-to-br from-purple-100 to-blue-100 rounded-2xl"> 👤 </div>

<h1 class="text-3xl font-bold text-center mb-6">Resident Login</h1>

<!-- ✅ GLOBAL ERROR -->
@if(session('error'))
<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif

<form id="loginForm" method="POST" action="{{ route('resident.login.post') }}">
@csrf

<!-- NAME -->
<input type="text" name="name" placeholder="Full Name"
class="w-full mb-3 p-3 border rounded"
value="{{ old('name') }}">
@error('name')
<p class="text-red-600 text-sm mb-2">{{ $message }}</p>
@enderror

<!-- EMAIL -->
<input type="email" name="email" placeholder="Email"
class="w-full mb-3 p-3 border rounded"
value="{{ old('email') }}">
@error('email')
<p class="text-red-600 text-sm mb-2">{{ $message }}</p>
@enderror

<!-- PHONE -->
<input type="tel" name="phone" placeholder="+27821234567 or 0821234567"
class="w-full mb-3 p-3 border rounded"
value="{{ old('phone') }}">
@error('phone')
<p class="text-red-600 text-sm mb-2">{{ $message }}</p>
@enderror

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
    <p class="text-gray-500 mb-6">Enter the 6-digit code sent to you</p>

    <!-- OTP INPUTS -->
    <div class="flex justify-center gap-2 mb-4">
      <input type="text" maxlength="1" class="otp-input" />
      <input type="text" maxlength="1" class="otp-input" />
      <input type="text" maxlength="1" class="otp-input" />
      <input type="text" maxlength="1" class="otp-input" />
      <input type="text" maxlength="1" class="otp-input" />
      <input type="text" maxlength="1" class="otp-input" />
    </div>

    <!-- ERROR -->
    <p id="otpError" class="text-red-500 text-sm hidden mb-3"></p>

    <!-- VERIFY BUTTON -->
    <button onclick="submitOTP()"
      class="w-full py-3 bg-purple-600 text-white rounded-lg font-bold mb-3">
      Verify OTP
    </button>

    <!-- RESEND -->
    <button id="resendBtn" onclick="resendOTP()"
      class="text-sm text-purple-600 font-semibold hidden">
      Resend OTP
    </button>

    <p id="timerText" class="text-sm text-gray-400"></p>

  </div>
</div>

<script>
const inputs = document.querySelectorAll(".otp-input");

// AUTO MOVE
inputs.forEach((input, index) => {
  input.addEventListener("input", (e) => {
    if (e.target.value.length === 1 && index < inputs.length - 1) {
      inputs[index + 1].focus();
    }
  });

  input.addEventListener("keydown", (e) => {
    if (e.key === "Backspace" && !input.value && index > 0) {
      inputs[index - 1].focus();
    }
  });
});

// OPEN MODAL (call this after login success)
function openOtpModal() {
  document.getElementById("otpModal").classList.remove("hidden");
  startTimer();
  inputs[0].focus();
}

// SUBMIT OTP
function submitOTP() {
  let otp = "";
  inputs.forEach(input => otp += input.value);

  if (otp.length !== 6) {
    showError("Enter complete OTP");
    return;
  }

  fetch("{{ route('otp.verify') }}", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": "{{ csrf_token() }}",
    "Accept": "application/json" // ✅ ADD
  },
  body: JSON.stringify({ otp: otp })
})
.then(async res => {
  if (!res.ok) {
    const data = await res.json();
    showError(data.message || "Invalid OTP");
    return;
  }

  return res.json();
})
.then(data => {
  if (data && data.success) {
    window.location.href = data.redirect;
  }
});
}

// ERROR DISPLAY
function showError(msg) {
  const err = document.getElementById("otpError");
  err.textContent = msg;
  err.classList.remove("hidden");
}

// TIMER
let timeLeft = 30;
function startTimer() {
  const timer = document.getElementById("timerText");
  const resendBtn = document.getElementById("resendBtn");

  resendBtn.classList.add("hidden");

  const interval = setInterval(() => {
    timeLeft--;
    timer.textContent = `Resend in ${timeLeft}s`;

    if (timeLeft <= 0) {
      clearInterval(interval);
      timer.textContent = "";
      resendBtn.classList.remove("hidden");
    }
  }, 1000);
}

// RESEND OTP
function resendOTP() {
  fetch("/resend-otp", { method: "POST",
    headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
  });

  timeLeft = 30;
  startTimer();
}
</script>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
  

  const formData = new FormData(this);

 fetch("{{ route('resident.login.post') }}", {
  method: "POST",
  headers: {
    "X-CSRF-TOKEN": "{{ csrf_token() }}",
    "Accept": "application/json" // ✅ ADD THIS
  },
  body: formData
})
.then(async res => {
  if (!res.ok) {
    const data = await res.json();

    if (data.errors) {
      let messages = Object.values(data.errors).flat().join("\n");
      alert(messages); // or show nicely in UI
    }
    return;
  }

  return res.json();
})
.then(data => {
  if (data && data.success) {
    openOtpModal();
  }
});
});
</script>
</body>
</html>