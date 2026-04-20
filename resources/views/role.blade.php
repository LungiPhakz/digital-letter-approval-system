<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CommunityLetters - Role Selection & Login</title>
 <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <script src="/_sdk/data_sdk.js"></script>
<script src="https://cdn.tailwindcss.com"></script>

<style>
* { box-sizing: border-box; }
html, body { height: 100%; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

.gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.gradient-secondary { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.card-modern { background: white; border-radius: 20px; box-shadow:0 10px 30px rgba(0,0,0,.08); transition: all 0.3s ease; }
.card-modern:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,.12); }

button:hover { transform: scale(1.05); transition: 0.3s ease; }
</style>
</head>
<body class="bg-gray-50 h-full w-full overflow-auto">

<!-- ================= ROLE SELECTION PAGE ================= -->
<div id="role-page" class="min-h-full w-full bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 flex items-center justify-center py-12 px-6">
    <div class="w-full max-w-2xl animate-fade-in">
     <div class="text-center mb-16">
      <h1 class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 mb-3">Welcome Back</h1>
      <p class="text-gray-600 text-lg">Choose your role to access the system</p>
     </div>

    <div class="grid md:grid-cols-2 gap-8">
      <!-- Resident -->
      <div class="card-modern text-center p-8 cursor-pointer border-2 border-purple-600 hover:shadow-lg transition" onclick="showResidentLogin()">
        <div class="text-7xl mb-6 inline-block p-6 bg-gradient-to-br from-purple-100 to-blue-100 rounded-2xl">
         👤
        </div>
       <h2 class="text-3xl font-bold text-gray-800 mb-3">Resident</h2>

        <p class="text-gray-600 mb-8 text-sm leading-relaxed">Request and manage your proof of residence letters with ease</p>
        <div class="space-y-3 text-sm text-gray-700 text-left">
         <p class="flex items-center"><span class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 text-white flex items-center justify-center text-xs mr-3 font-bold">✓</span> Submit requests instantly</p>
         <p class="flex items-center"><span class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 text-white flex items-center justify-center text-xs mr-3 font-bold">✓</span> Track status in real-time</p>
         <p class="flex items-center"><span class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 text-white flex items-center justify-center text-xs mr-3 font-bold">✓</span> Download official letters</p>
        </div>
        <a href="{{ route('resident.login') }}">
<button class="w-full mt-8 px-6 py-3 gradient-primary text-white rounded-lg font-bold">
Login as Resident →
</button>
</a>
        </div>

      <!-- Councilor/Admin -->
      <div class="card-modern text-center p-8 cursor-pointer border-2 border-purple-700 hover:shadow-lg transition" onclick="showCouncilorLogin()">
         <div class="text-7xl mb-6 inline-block p-6 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl">
         👨‍⚖️
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-3">Councilor/Admin</h2>
        <p class="text-gray-600 mb-8 text-sm leading-relaxed">Manage, approve, and generate official letters</p>
        <div class="space-y-3 text-sm text-gray-700 text-left">
         <p class="flex items-center"><span class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white flex items-center justify-center text-xs mr-3 font-bold">✓</span> Review all requests</p>
         <p class="flex items-center"><span class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white flex items-center justify-center text-xs mr-3 font-bold">✓</span> Approve/Reject with reason</p>
         <p class="flex items-center"><span class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white flex items-center justify-center text-xs mr-3 font-bold">✓</span> Advanced analytics &amp; reports</p>
         <a href="{{ route('councilor.login') }}">
        <button class="w-full mt-8 px-6 py-3 gradient-secondary text-white rounded-lg font-bold hover:shadow-lg transition">Login as Councilor →</button>
        </a>
      </div>
    </div>
  </div>
  <div class="mt-12 text-center">
  <a href="{{ route('home') }}" class="text-purple-600 font-semibold hover:text-purple-700">
← Back to Home
</a>
  
     </div>
</div>

    </div>
  </div>
</div>

<script>
// Show resident login page
function showResidentLogin() {
    document.getElementById('role-page').classList.add('hidden');
    document.getElementById('resident-login-page').classList.remove('hidden');
}

// Show councilor login page
function showCouncilorLogin() {
    document.getElementById('role-page').classList.add('hidden');
    document.getElementById('councilor-login-page').classList.remove('hidden');
}

// Back to role selection page
function showRolePage() {
    document.getElementById('resident-login-page').classList.add('hidden');
    document.getElementById('councilor-login-page').classList.add('hidden');
    document.getElementById('role-page').classList.remove('hidden');
}
</script>

</body>
</html>