<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CommunityLetters - Resident Login</title>

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com/3.4.17"></script>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<!-- PDF Generator -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- SDK -->


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

<body class="bg-gradient-to-br from-purple-50 via-blue-50 to-purple-100 min-h-screen flex items-center justify-center px-6">

<div class="max-w-md w-full">

<div class="card-modern p-8">

<!-- Header -->

<div class="text-center mb-8">

<div class="text-6xl mb-4 inline-block p-4 bg-gradient-to-br from-purple-100 to-blue-100 rounded-2xl">
👤
</div>

<h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-blue-600">
Resident Login
</h1>

<p class="text-gray-600 mt-2">
Access your proof of residence requests
</p>

</div>

<!-- Login Form -->

<form method="POST" action="{{ route('resident.login.post') }}">

@csrf

<!-- FULL NAME -->
<div>
  <input
    type="text"
    id="name"
    name="name"
    placeholder="Full Name"
    required
    class="w-full mb-1 p-3 border rounded-lg focus:outline-none"
  />
  <p id="name_error" class="text-sm text-red-600 hidden mb-3"></p>
</div>

<!-- EMAIL -->
<div>
  <input
    type="email"
    id="email"
    name="email"
    placeholder="Email Address"
    required
    class="w-full mb-1 p-3 border rounded-lg focus:outline-none"
  />
  <p id="email_error" class="text-sm text-red-600 hidden mb-3"></p>
</div>

<!-- PHONE -->
<div>
  <input
  type="tel"
  id="phone"
  name="phone"
  placeholder="Phone Number (e.g. +27/0712345678)"
  maxlength="10"
  inputmode="numeric"
  class="w-full mb-1 p-3 border rounded-lg focus:outline-none"
/>
<p id="phone_error" class="text-sm text-red-600 hidden mb-4"></p>
</div>

<button
type="submit"
class="w-full py-3 gradient-primary text-white rounded-lg font-bold hover:shadow-lg transition"
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

</div>

</div>


<script>
// ===== FULL NAME VALIDATION =====
function validateFullName(name) {
    const parts = name.trim().split(/\s+/);
    if (parts.length < 2) {
        return "Please enter your full name (name and surname).";
    }
    return null;
}

// ===== EMAIL VALIDATION =====
function validateEmail(email) {
    const pattern = /^[^\s@]+@[^\s@]+\.[a-zA-Z]{2,}$/;
    if (!pattern.test(email)) {
        return "Enter a valid email address.";
    }
    return null;
}

// ===== SA PHONE VALIDATION =====
function validatePhone(phone) {
    const clean = phone.replace(/\D/g, ''); // remove anything not number

    if (clean.length === 0) return "Phone number is required.";

    if (!/^(\+27|0)[6-8][0-9]{8}$/.test(phone)) {
        return "Enter valid SA number (0821234567 or +27821234567).";
    }

    return null;
}


// ===== ELEMENTS =====
const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const phoneInput = document.getElementById("phone");

// ===== ERROR ELEMENTS =====
const nameError = document.getElementById("name_error");
const emailError = document.getElementById("email_error");
const phoneError = document.getElementById("phone_error");

// ===== LIVE VALIDATION =====
nameInput.addEventListener("input", () => {
    const error = validateFullName(nameInput.value);

    if (error) {
        nameError.textContent = error;
        nameError.classList.remove("hidden");
        nameInput.classList.add("border-red-500");
    } else {
        nameError.classList.add("hidden");
        nameInput.classList.remove("border-red-500");
        nameInput.classList.add("border-green-500");
    }
});

emailInput.addEventListener("input", () => {
    const error = validateEmail(emailInput.value);

    if (error) {
        emailError.textContent = error;
        emailError.classList.remove("hidden");
        emailInput.classList.add("border-red-500");
    } else {
        emailError.classList.add("hidden");
        emailInput.classList.remove("border-red-500");
        emailInput.classList.add("border-green-500");
    }
});

phoneInput.addEventListener("input", () => {
    const error = validatePhone(phoneInput.value);

    if (error) {
        phoneError.textContent = error;
        phoneError.classList.remove("hidden");
        phoneInput.classList.add("border-red-500");
        phoneInput.classList.remove("border-green-500");
    } else {
        phoneError.classList.add("hidden");
        phoneInput.classList.remove("border-red-500");
        phoneInput.classList.add("border-green-500");
    }
});

phoneInput.addEventListener("keypress", function(e) {
    if (!/[0-9]/.test(e.key)) {
        e.preventDefault();
    }
});

// ===== FORM SUBMIT VALIDATION =====
document.querySelector("form").addEventListener("submit", function(e) {

    const nameErr = validateFullName(nameInput.value);
    const emailErr = validateEmail(emailInput.value);
    const phoneErr = validatePhone(phoneInput.value);

    if (nameErr || emailErr || phoneErr) {
        e.preventDefault();

        if (nameErr) {
            nameError.textContent = nameErr;
            nameError.classList.remove("hidden");
            nameInput.classList.add("border-red-500");
        }

        if (emailErr) {
            emailError.textContent = emailErr;
            emailError.classList.remove("hidden");
            emailInput.classList.add("border-red-500");
        }

        if (phoneErr) {
            phoneError.textContent = phoneErr;
            phoneError.classList.remove("hidden");
            phoneInput.classList.add("border-red-500");
        }
    }
});
</script>
</body>
</html>