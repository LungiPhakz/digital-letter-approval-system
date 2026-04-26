<!DOCTYPE html>
<html>
<head>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white w-96 p-6 rounded-xl shadow-lg">

    <h2 class="text-2xl font-bold text-center">OTP Verification</h2>
    <p class="text-gray-500 text-center mt-2 mb-6">
        Enter the 6-digit code sent to your phone & email
    </p>

    @if(session('error'))
        <p class="text-red-500 text-center mb-3">
            {{ session('error') }}
        </p>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <input type="text"
               name="otp"
               maxlength="6"
               class="w-full text-center text-2xl tracking-widest border p-3 rounded-lg mb-4"
               placeholder="------">

        <button class="w-full bg-purple-600 text-white py-3 rounded-lg font-bold">
            Verify OTP
        </button>
    </form>

    <p class="text-sm text-gray-500 text-center mt-4">
        OTP expires in 5 minutes
    </p>

</div>

</body>
</html>