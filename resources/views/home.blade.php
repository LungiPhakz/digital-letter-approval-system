<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Community Letter Management System</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <script src="/_sdk/data_sdk.js"></script>
  <script>

     function scrollToHero() {
      const section = document.getElementById('hero');
      if (section) section.scrollIntoView({ behavior: 'smooth' });
    }

    function scrollToAbout() {
      const section = document.getElementById('about-section');
      if (section) section.scrollIntoView({ behavior: 'smooth' });
    }

    function scrollToFeatures() {
      const section = document.getElementById('features-section');
      if (section) section.scrollIntoView({ behavior: 'smooth' });
    }

    function scrollToContact() {
      const section = document.getElementById('contact-section');
      if (section) section.scrollIntoView({ behavior: 'smooth' });
    }

    </script>
  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .gradient-primary {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .gradient-secondary {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .gradient-success {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .gradient-warm {
      background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    .animate-fade-in {
      animation: fadeIn 0.6s ease-out;
    }

    .animate-slide-in-up {
      animation: slideInUp 0.6s ease-out;
    }

    .animate-scale-in {
      animation: scaleIn 0.5s ease-out;
    }

    .card-modern {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
    }

    .card-modern:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .card-gradient {
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
      position: relative;
      overflow: hidden;
    }

    .card-gradient::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.3), transparent);
      z-index: 1;
    }

    .login-card {
      border-radius: 25px;
      border: 2px solid transparent;
      background-clip: padding-box;
      padding: 40px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .login-card-resident {
      border-color: #667eea;
      background: linear-gradient(white, white) padding-box;
    }

    .login-card-resident:hover {
      border-color: #667eea;
      box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
      transform: translateY(-8px);
    }

    .login-card-admin {
      border-color: #764ba2;
      background: linear-gradient(white, white) padding-box;
    }

    .login-card-admin:hover {
      border-color: #764ba2;
      box-shadow: 0 20px 40px rgba(118, 75, 162, 0.2);
      transform: translateY(-8px);
    }

    .stamp {
      border: 2px solid rgba(102, 126, 234, 0.3);
      border-radius: 50%;
      padding: 20px;
      display: inline-block;
      transform: rotate(-15deg);
      font-weight: bold;
      color: rgba(102, 126, 234, 0.5);
      font-size: 14px;
      text-align: center;
    }

    .letter-template {
      background: white;
      border: 1px solid #ddd;
      padding: 40px;
      font-family: 'Georgia', serif;
      line-height: 1.8;
    }

    .notification-toast {
      position: fixed;
      bottom: 20px;
      right: 20px;
      padding: 16px 24px;
      border-radius: 12px;
      color: white;
      font-weight: 600;
      z-index: 1000;
      animation: slideInUp 0.3s ease-out;
    }

    .toast-success {
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .toast-error {
      background: linear-gradient(135deg, #f5576c 0%, #fa709a 100%);
    }

    .toast-info {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .status-badge {
      display: inline-block;
      padding: 6px 14px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-pending {
      background: #fef3c7;
      color: #92400e;
    }

    .status-approved {
      background: #d1fae5;
      color: #065f46;
    }

    .status-rejected {
      background: #fee2e2;
      color: #7f1d1d;
    }

    .status-completed {
      background: #d1fae5;
      color: #065f46;
    }

    .stat-card {
      background: white;
      border-radius: 16px;
      padding: 24px;
      border-left: 5px solid;
      transition: all 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-card-purple {
      border-left-color: #667eea;
    }

    .stat-card-pink {
      border-left-color: #f5576c;
    }

    .stat-card-cyan {
      border-left-color: #00f2fe;
    }

    .stat-card-orange {
      border-left-color: #fee140;
    }

    .sidebar-active {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 12px;
    }

    .nav-item {
      transition: all 0.3s ease;
      border-radius: 12px;
    }

    .nav-item:hover {
      background: rgba(102, 126, 234, 0.1);
    }

    .chart-container {
      position: relative;
      height: 300px;
      margin-bottom: 30px;
    }
  </style>

  <style>body { box-sizing: border-box; }</style>
 </head>
 <body class="h-full bg-gray-50">
  <div id="app" class="h-full w-full overflow-auto"><!-- HOME PAGE -->
   <div id="home-page" class="min-h-full w-full bg-white"><!-- Premium Navigation -->
    <nav class="w-full bg-white shadow-lg sticky top-0 z-40 backdrop-blur-md bg-opacity-95">
     <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
      <div class="flex items-center gap-3">
       <div class="text-3xl">
        📜
       </div>
       <div class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600">
        CommunityLetters
       </div>
      </div>
      <div class="hidden md:flex gap-8"><button onclick="scrollToHero()" class="px-4 py-2 text-gray-700 hover:text-purple-600 font-medium transition duration-300">Home</button> <button onclick="scrollToAbout()" class="px-4 py-2 text-gray-700 hover:text-purple-600 font-medium transition duration-300">How It Works</button> <button onclick="scrollToFeatures()" class="px-4 py-2 text-gray-700 hover:text-purple-600 font-medium transition duration-300">Features</button> <button onclick="scrollToContact()" class="px-4 py-2 text-gray-700 hover:text-purple-600 font-medium transition duration-300">Contact</button>
      </div><button onclick="window.location.href='{{ route('role') }}'"
class="px-8 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold hover:shadow-xl transition duration-300 transform hover:scale-105">
Get Started
</button>
     </div>
    </nav><!-- Hero Section - Storytelling -->
    <section id="hero" class="w-full min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 text-white relative overflow-hidden flex items-center"><!-- Animated Background Elements -->
     <div class="absolute top-0 left-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
     <div class="absolute bottom-0 right-0 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
     <div class="max-w-7xl mx-auto px-6 relative z-10 py-20">
      <div class="grid md:grid-cols-2 gap-12 items-center"><!-- Story Content -->
       <div class="animate-fade-in">
        <div class="inline-block px-4 py-2 bg-purple-500 bg-opacity-20 rounded-full border border-purple-400 mb-6">
         <p class="text-purple-200 text-sm font-semibold">✨ The Future of Community Services</p>
        </div>
        <h1 class="text-6xl font-bold mb-6 leading-tight">Your Community, Digitized</h1>
        <p class="text-xl text-gray-300 mb-4 leading-relaxed">No more waiting in long queues. No more paperwork. Just a few clicks and your official proof of residence letter is ready. We've reimagined how communities connect with their residents.</p>
        <p class="text-lg text-gray-400 mb-8 leading-relaxed">From submission to signature, every step is transparent, secure, and professional. Your trust is our foundation.</p>
        <div class="flex gap-4 flex-wrap"><button  onclick="window.location.href='{{ route('role') }}'" class="px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-bold hover:shadow-2xl transition duration-300 transform hover:scale-105">Start Your Request</button> <button onclick="scrollToAbout()" class="px-8 py-4 border-2 border-white text-white rounded-xl font-bold hover:bg-white hover:text-purple-900 transition duration-300">Learn More</button>
        </div>
       </div><!-- Visual Story -->
       <div class="relative h-96 md:h-full animate-slide-in-up" style="animation-delay: 0.2s;">
        <div class="absolute inset-0 bg-gradient-to-t from-purple-600 to-pink-600 rounded-3xl opacity-10"></div>
        <div class="absolute inset-4 bg-white bg-opacity-5 rounded-2xl backdrop-blur-xl border border-white border-opacity-10 p-8 flex items-center justify-center">
         <div class="text-center">
          <div class="text-8xl mb-6 animate-bounce">
           📋
          </div>
          <p class="text-2xl font-bold text-purple-200 mb-2">Instant Processing</p>
          <p class="text-gray-300">From request to official letter in minutes</p>
         </div>
        </div>
       </div>
      </div>
     </div><!-- Scroll Indicator -->
     <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
      <div class="text-white text-center">
       <p class="text-sm mb-2">Scroll to explore</p>
       <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
       </svg>
      </div>
     </div>
    </section><!-- How It Works - Beautiful Timeline -->
    <section id="about-section" class="w-full py-24 bg-white">
     <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16 animate-fade-in">
       <h2 class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 mb-4">How It Works</h2>
       <p class="text-xl text-gray-600">A seamless journey from application to your official letter</p>
      </div>
      <div class="grid md:grid-cols-4 gap-8 mb-16"><!-- Step 1 -->
       <div class="relative group animate-slide-in-up" style="animation-delay: 0s;">
        <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-transparent hover:border-purple-600 transition duration-300 h-full">
         <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
          1
         </div>
         <div class="text-5xl mb-6 mt-4">
          📝
         </div>
         <h3 class="text-2xl font-bold text-gray-800 mb-3">Apply</h3>
         <p class="text-gray-600">Fill out a simple form with your basic information. Takes less than 2 minutes.</p>
        </div>
        <div class="absolute top-1/2 -right-8 w-16 h-1 bg-gradient-to-r from-purple-600 to-transparent hidden md:block"></div>
       </div><!-- Step 2 -->
       <div class="relative group animate-slide-in-up" style="animation-delay: 0.1s;">
        <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-transparent hover:border-purple-600 transition duration-300 h-full">
         <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
          2
         </div>
         <div class="text-5xl mb-6 mt-4">
          📊
         </div>
         <h3 class="text-2xl font-bold text-gray-800 mb-3">Review</h3>
         <p class="text-gray-600">Our council reviews your application with utmost care and transparency.</p>
        </div>
        <div class="absolute top-1/2 -right-8 w-16 h-1 bg-gradient-to-r from-purple-600 to-transparent hidden md:block"></div>
       </div><!-- Step 3 -->
       <div class="relative group animate-slide-in-up" style="animation-delay: 0.2s;">
        <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-transparent hover:border-purple-600 transition duration-300 h-full">
         <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
          3
         </div>
         <div class="text-5xl mb-6 mt-4">
          ✍️
         </div>
         <h3 class="text-2xl font-bold text-gray-800 mb-3">Sign</h3>
         <p class="text-gray-600">Official signature and seal are digitally applied to your letter.</p>
        </div>
        <div class="absolute top-1/2 -right-8 w-16 h-1 bg-gradient-to-r from-purple-600 to-transparent hidden md:block"></div>
       </div><!-- Step 4 -->
       <div class="relative group animate-slide-in-up" style="animation-delay: 0.3s;">
        <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-transparent hover:border-purple-600 transition duration-300 h-full">
         <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg">
          4
         </div>
         <div class="text-5xl mb-6 mt-4">
          📥
         </div>
         <h3 class="text-2xl font-bold text-gray-800 mb-3">Receive</h3>
         <p class="text-gray-600">Instantly download your official letter. Ready to use anywhere.</p>
        </div>
       </div>
      </div>
     </div>
    </section><!-- Features Section -->
    <section id="features-section" class="w-full py-24 bg-gradient-to-b from-gray-50 to-white">
     <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16 animate-fade-in">
       <h2 class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 mb-4">Why Choose Us?</h2>
       <p class="text-xl text-gray-600">Experience the future of community services</p>
      </div>
      <div class="grid md:grid-cols-2 gap-12 items-center mb-16"><!-- Feature Description -->
       <div class="space-y-6 animate-slide-in-up">
        <div class="flex gap-4">
         <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center text-white text-xl">
          ⚡
         </div>
         <div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Lightning Fast</h3>
          <p class="text-gray-600">Get your official letter within minutes, not days</p>
         </div>
        </div>
        <div class="flex gap-4">
         <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center text-white text-xl">
          🔐
         </div>
         <div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Bank-Grade Security</h3>
          <p class="text-gray-600">Your data is encrypted and protected at all times</p>
         </div>
        </div>
        <div class="flex gap-4">
         <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center text-white text-xl">
          📜
         </div>
         <div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Official &amp; Legal</h3>
          <p class="text-gray-600">Digitally signed and sealed by authorized officials</p>
         </div>
        </div>
        <div class="flex gap-4">
         <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg flex items-center justify-center text-white text-xl">
          📱
         </div>
         <div>
          <h3 class="text-xl font-bold text-gray-800 mb-2">Access Anytime</h3>
          <p class="text-gray-600">Available 24/7 on any device, anywhere in the world</p>
         </div>
        </div>
       </div><!-- Feature Visual -->
       <div class="relative animate-scale-in">
        <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-3xl p-1">
         <div class="bg-white rounded-3xl p-8 text-center">
          <div class="text-7xl mb-4">
           ✨
          </div>
          <p class="text-2xl font-bold text-gray-800 mb-2">Premium Experience</p>
          <p class="text-gray-600 mb-6">Clean, intuitive interface designed for everyone</p>
          <div class="bg-gradient-to-r from-purple-100 to-pink-100 rounded-2xl p-6">
           <p class="text-gray-700">Join thousands of satisfied residents</p>
          </div>
         </div>
        </div>
       </div>
      </div>
     </div>
    </section><!-- Trust & Stats Section -->
    <section class="w-full py-16 bg-white border-t border-gray-200">
     <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-4 gap-8 text-center">
       <div class="animate-fade-in">
        <div class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
         10K+
        </div>
        <p class="text-gray-600 mt-2">Happy Residents</p>
       </div>
       <div class="animate-fade-in" style="animation-delay: 0.1s;">
        <div class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
         50+
        </div>
        <p class="text-gray-600 mt-2">Communities Served</p>
       </div>
       <div class="animate-fade-in" style="animation-delay: 0.2s;">
        <div class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
         99.9%
        </div>
        <p class="text-gray-600 mt-2">Uptime Guarantee</p>
       </div>
       <div class="animate-fade-in" style="animation-delay: 0.3s;">
        <div class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600">
         &lt;5min
        </div>
        <p class="text-gray-600 mt-2">Average Processing</p>
       </div>
      </div>
     </div>
    </section><!-- Contact Section -->
    <section id="contact-section" class="w-full py-24 bg-gradient-to-br from-purple-50 to-pink-50">
     <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16 animate-fade-in">
       <h2 class="text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-pink-600 mb-4">Get In Touch</h2>
       <p class="text-xl text-gray-600">We're here to help and answer any questions</p>
      </div>
      <div class="grid md:grid-cols-3 gap-8 mb-16">
       <div class="bg-white rounded-2xl shadow-lg p-8 text-center border-t-4 border-purple-600 hover:shadow-xl transition">
        <div class="text-5xl mb-4">
         📧
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Email</h3>
        <p class="text-gray-600">support@communityletters.local</p>
       </div>
       <div class="bg-white rounded-2xl shadow-lg p-8 text-center border-t-4 border-pink-600 hover:shadow-xl transition">
        <div class="text-5xl mb-4">
         📞
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Phone</h3>
        <p class="text-gray-600">+1 (555) 123-4567</p>
       </div>
       <div class="bg-white rounded-2xl shadow-lg p-8 text-center border-t-4 border-blue-600 hover:shadow-xl transition">
        <div class="text-5xl mb-4">
         🏢
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Office</h3>
        <p class="text-gray-600">Community Center, Main St</p>
       </div>
      </div>
     </div>
    </section><!-- CTA Section -->
    <section class="w-full py-16 bg-gradient-to-r from-purple-600 to-pink-600 text-white">
     <div class="max-w-7xl mx-auto px-6 text-center">
      <h2 class="text-4xl font-bold mb-6">Ready to Get Your Official Letter?</h2>
      <p class="text-xl mb-8 opacity-90">Join thousands of residents using our service today</p>
      <button  onclick="window.location.href='{{ route('role') }}'" class="px-8 py-4 bg-white text-purple-600 rounded-xl font-bold text-lg hover:shadow-2xl transition duration-300 transform hover:scale-105">Start Now - It's Free!</button>
     </div>
    </section><!-- Footer -->
    <footer class="w-full bg-gray-900 text-gray-300 py-12">
     <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-4 gap-8 mb-8">
       <div>
        <div class="text-2xl font-bold text-white mb-4">
         📜 CommunityLetters
        </div>
        <p class="text-sm">Making community services accessible to everyone</p>
       </div>
       <div>
        <h4 class="font-semibold text-white mb-4">Product</h4>
        <ul class="space-y-2 text-sm">
         <li><a href="#" class="hover:text-white transition">How it works</a></li>
         <li><a href="#" class="hover:text-white transition">Features</a></li>
         <li><a href="#" class="hover:text-white transition">Pricing</a></li>
        </ul>
       </div>
       <div>
        <h4 class="font-semibold text-white mb-4">Company</h4>
        <ul class="space-y-2 text-sm">
         <li><a href="#" class="hover:text-white transition">About</a></li>
         <li><a href="#" class="hover:text-white transition">Blog</a></li>
         <li><a href="#" class="hover:text-white transition">Careers</a></li>
        </ul>
       </div>
       <div>
        <h4 class="font-semibold text-white mb-4">Legal</h4>
        <ul class="space-y-2 text-sm">
         <li><a href="#" class="hover:text-white transition">Privacy</a></li>
         <li><a href="#" class="hover:text-white transition">Terms</a></li>
         <li><a href="#" class="hover:text-white transition">Security</a></li>
        </ul>
       </div>
      </div>
      <div class="border-t border-gray-700 pt-8 text-center text-sm">
       <p>© 2024 Digital Community Letter Management. All rights reserved.</p>
      </div>
     </div>
    </footer>

