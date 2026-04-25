<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Councilor Dashboard</title>

    <!-- Tailwind CSS CDN (optional, easier than writing all classes) -->
    
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  
  
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        /* Custom Dashboard Styles */
         * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
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

    .hidden {
  display: none !important;
  
}

#signing-modal * {
  pointer-events: auto;
}

#signing-modal,
#approval-modal,
#letter-preview-modal {
  pointer-events: auto;
  z-index: 9999;
}

    #signature-canvas {
    touch-action: none;
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

    @media (max-width: 640px) {
  .card-modern {
    border-radius: 12px;
    padding: 16px !important;
  }

  h1 {
    font-size: 1.5rem !important;
  }

  h2 {
    font-size: 1.25rem !important;
  }

  h3 {
    font-size: 1.1rem !important;
  }

  .stat-card {
    padding: 16px;
  }
}
    </style>
</head>
<body>

@php
$total = $requests->count();
$approved = $requests->where('status', 'Approved')->count();
$rejected = $requests->where('status', 'Rejected')->count();

$avgHours = 0;
$completed = $requests->whereNotNull('approved_at');

if ($completed->count() > 0) {
    $totalHours = $completed->sum(function($r) {
        return \Carbon\Carbon::parse($r->created_at)
            ->diffInHours(\Carbon\Carbon::parse($r->approved_at));
    });
    $avgHours = round($totalHours / $completed->count(), 1);
}
@endphp


<div id="councilor-dashboard-page" class="min-h-screen w-full">

    <!-- Header -->
    <div class="w-full gradient-primary text-white py-6 shadow-lg">
       <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

    <div>
        <h1 class="text-3xl font-bold">📊 Councilor Dashboard</h1>
        <p class="opacity-90 mt-1">Request Management System</p>
    </div>

    <!-- RIGHT SIDE WRAPPER -->
    <div class="flex justify-end md:justify-end w-full md:w-auto">
      
        <form method="POST" action="{{ route('logout') }}">
    @csrf

    <button
        type="submit"
        class="px-6 py-2 bg-white text-purple-600 rounded-lg font-bold hover:bg-gray-100 transition">
        Logout
    </button>
</form>
    </div>

</div>

        <!-- Navigation Tabs -->
        <div class="max-w-7xl mx-auto px-4 flex flex-wrap gap-2 border-t border-purple-400 pt-4 overflow-x-auto">
            <button onclick="switchCouncilorTab('dashboard')" id="tab-dashboard" class="px-6 py-2 border-b-2 border-white font-semibold hover:opacity-80 transition">Dashboard</button>
            <button onclick="switchCouncilorTab('requests')" id="tab-requests" class="px-6 py-2 border-b-2 border-transparent font-semibold hover:opacity-80 transition">Requests</button>
            <button onclick="generateReport(), switchCouncilorTab('reports')" id="tab-reports" class="px-6 py-2 border-b-2 border-transparent font-semibold hover:opacity-80 transition">Reports</button>
            <button onclick="switchCouncilorTab('profile')" id="tab-profile" class="px-6 py-2 border-b-2 border-transparent font-semibold hover:opacity-80 transition">Profile</button>
        </div>
    </div>

    <!-- DASHBOARD TAB -->
    <div id="dashboard-tab" class="max-w-7xl mx-auto px-6 py-12">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card stat-card-purple">
                <div class="text-4xl mb-2">📋</div>
                <p class="opacity-75 text-sm text-gray-600">Total Requests</p>
                <p class="text-4xl font-bold text-purple-600" id="stat-total">{{ $requests->count() }}</p>
            </div>
            <div class="stat-card stat-card-pink">
                <div class="text-4xl mb-2">⏳</div>
                <p class="opacity-75 text-sm text-gray-600">Pending</p>
                <p class="text-4xl font-bold text-pink-600" id="stat-pending">{{ $requests->where('status','Pending')->count() }}</p>
            </div>
            <div class="stat-card stat-card-cyan">
                <div class="text-4xl mb-2">✅</div>
                <p class="opacity-75 text-sm text-gray-600">Approved</p>
                <p class="text-4xl font-bold text-cyan-600" id="stat-approved">{{ $requests->where('status','Approved')->count() }}</p>
            </div>
            <div class="stat-card stat-card-orange">
                <div class="text-4xl mb-2">❌</div>
                <p class="opacity-75 text-sm text-gray-600">Rejected</p>
                <p class="text-4xl font-bold text-orange-600" id="stat-rejected">{{ $requests->where('status','Rejected')->count() }}</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid md:grid-cols-2 gap-6">
            <div class="card-modern p-6 shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Status Distribution</h3>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            <div class="card-modern p-6 shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Purpose Types</h3>
                <div class="chart-container">
                    <canvas id="typesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Trends Chart -->
        <div class="card-modern p-6 shadow-lg mt-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Requests Over Time</h3>
            <div class="chart-container">
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- REQUESTS TAB -->
    <div id="requests-tab" class="hidden max-w-7xl mx-auto px-6 py-12">
        <div class="card-modern p-6 shadow-lg mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Filters & Search</h3>
            <div class="grid md:grid-cols-4 gap-4">
                 <input type="text" name="search" placeholder="Search Letter #" class="p-2 border rounded" value="{{ request('search') }}" />

        <input type="date" name="from_date" class="p-2 border rounded" value="{{ request('from_date') }}" />

        <input type="date" name="to_date" class="p-2 border rounded" value="{{ request('to_date') }}" />

                <div class="flex gap-2">
            <button onclick="applyFilters()" class="flex-1 px-6 py-2 gradient-primary text-white rounded-lg font-semibold">
    Apply
</button>

            <button type="button"
        onclick="resetFilters()"
        class="flex-1 px-6 py-2 bg-gray-300 text-center rounded-lg font-semibold">
    Reset
</button>

        </div>
            </div>
        </div>
        <div class="card-modern p-6 shadow-lg overflow-x-auto">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Requests Table</h3>
            <table class="w-full table-auto border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">ID</th>
                        <th class="border p-2">Name</th>
                        <th class="border p-2">Type</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Actions</th>
                    </tr>
                </thead>
               <tbody id="councilor-requests-list">
    @forelse($requests as $request)
        <tr>
            <td class="border p-2">{{ $request->id }}</td>
            <td class="border p-2">{{ $request->name }}</td>
            <td class="border p-2">{{ $request->type }}</td>

            <td class="border p-2">
                <span class="status-badge">
                    {{ $request->status }}
                </span>
            </td>

            <td class="border p-2">
                <button onclick="openApprovalModal({{ $request->id }})"
    class="px-2 py-1 bg-green-500 text-white rounded">
    Approve
</button>

                <form method="POST" action="{{ route('councilor.reject', $request->id) }}" class="inline">
                    @csrf
                    <button class="px-2 py-1 bg-red-500 text-white rounded">Reject</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center p-4 text-gray-500">
                No requests found
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>

    <!-- REPORTS TAB -->
    <div id="reports-tab" class="hidden max-w-7xl mx-auto px-6 py-12">
     <div class="mb-8">
      <h2 class="text-3xl font-bold text-gray-800 mb-6">Reports &amp; Analytics</h2><!-- Export Options -->
      <div class="card-modern p-6 shadow-lg mb-8">
       <h3 class="text-xl font-bold text-gray-800 mb-4">Export Reports</h3>
       <div class="flex flex-wrap gap-4">
        <button onclick="exportReportPDF()" class="px-6 py-3 gradient-secondary text-white rounded-lg font-bold hover:shadow-lg transition flex items-center gap-2"> 📄 Export as PDF </button> <button onclick="exportReportCSV()" class="px-6 py-3 bg-green-600 text-white rounded-lg font-bold hover:shadow-lg transition flex items-center gap-2"> 📊 Export as CSV </button>
       </div>
      </div><!-- Report Filters -->
      <div class="card-modern p-6 shadow-lg mb-8">
       <h3 class="text-xl font-bold text-gray-800 mb-4">Report Filters</h3>
       <div class="grid md:grid-cols-3 gap-4">
        <div><label class="block text-gray-700 font-semibold mb-2">From Date</label> <input type="date" id="report-from-date" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition">
        </div>
        <div><label class="block text-gray-700 font-semibold mb-2">To Date</label> <input type="date" id="report-to-date" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition">
        </div>
        <div class="flex items-end">
         <button onclick="generateReport()" class="w-full px-6 py-2 gradient-primary text-white rounded-lg font-bold hover:shadow-lg transition">Generate Report</button>
        <button onclick="resetReportFilters()" 
    class="w-full px-6 py-2 gradient-primary text-white rounded-lg font-bold hover:shadow-lg transition">
    ↻ Reset
</button>
        </div>
       </div>
      </div><!-- Report Summary -->
      <div class="grid md:grid-cols-3 gap-6 mb-8">
       <div class="card-modern p-6 border-l-4 border-purple-600 shadow-lg">
        <p class="text-gray-600 text-sm">Total Requests</p>
        <p class="text-3xl font-bold text-purple-600" id="report-total-requests">0</p>
       </div>
       <div class="card-modern p-6 border-l-4 border-green-600 shadow-lg">
        <p class="text-gray-600 text-sm">Approval Rate</p>
        <p class="text-3xl font-bold text-green-600" id="report-approval-rate">0%</p>
       </div>
       <div class="card-modern p-6 border-l-4 border-orange-600 shadow-lg">
        <p class="text-gray-600 text-sm">Avg Processing Time</p>
        <p class="text-3xl font-bold text-orange-600" id="report-avg-time">0 days</p>
       </div>
      </div><!-- Detailed Report Table -->
      <div class="card-modern shadow-lg overflow-x-auto">
       <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white">
        <h3 class="text-xl font-bold">Detailed Report</h3>
       </div>
       <div class="overflow-x-auto">
         <table class="min-w-[600px] w-full table-auto border-collapse border border-gray-200">
         <thead>
          <tr class="bg-gray-100 border-b">
           <th class="px-6 py-3 text-left font-semibold text-gray-700">Letter #</th>
           <th class="px-6 py-3 text-left font-semibold text-gray-700">Resident</th>
           <th class="px-6 py-3 text-left font-semibold text-gray-700">Type</th>
           <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
           <th class="px-6 py-3 text-left font-semibold text-gray-700">Submitted</th>
           <th class="px-6 py-3 text-left font-semibold text-gray-700">Days to Process</th>
            <th class="px-6 py-3 text-left font-semibold text-gray-700">Delete</th>
          </tr>
         </thead>
         <tbody id="report-details-list">
          <tr class="border-b">
           <td colspan="6" class="px-6 py-8 text-center text-gray-500">Click "Generate Report" to view detailed data</td>
          </tr>
         </tbody>
        </table>
       </div>
      </div>
     </div>


    </div><!-- PROFILE TAB -->
    <div id="profile-tab" class="hidden max-w-7xl mx-auto px-6 py-12">
     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Profile Card -->
      <div class="card-modern p-8 shadow-lg">
       <div class="text-center mb-8">
        <div class="text-6xl mb-4 inline-block p-4 bg-gradient-to-br from-purple-100 to-pink-100 rounded-2xl">
         👨‍⚖️
        </div>
        <h2 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
        <p class="text-gray-600 mt-2">Council & Community Services</p>
       </div>
       <div class="space-y-4">
        <div class="pb-4 border-b">
         <p class="text-sm text-gray-600">Email</p>
         <p class="text-lg font-semibold text-gray-800"> {{ auth()->user()->email }}</p>
        </div>
        <div class="pb-4 border-b">
         <p class="text-sm text-gray-600">Role</p>
         <p class="text-lg font-semibold text-gray-800">Administrator</p>
        </div>
        <div class="pb-4 border-b">
         <p class="text-sm text-gray-600">Department</p>
         <p class="text-lg font-semibold text-gray-800">Community Services</p>
        </div>
        <div>
         <p class="text-sm text-gray-600">Access Level</p>
         <p class="text-lg font-semibold"><span class="inline-block px-3 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full text-sm font-bold">Full Access</span></p>
        </div>
       </div>
      </div><!-- Statistics Summary -->
      <div class="space-y-6">
       <div class="card-modern p-6 shadow-lg border-l-4 border-purple-600">
        <p class="text-gray-600 text-sm">Total Requests Managed</p>
        <p class="text-4xl font-bold text-purple-600" id="profile-total-managed">{{ $total }}</p>
        <p class="text-xs text-gray-500 mt-2">Since administrator access</p>
       </div>
       <div class="card-modern p-6 shadow-lg border-l-4 border-green-600">
        <p class="text-gray-600 text-sm">Requests Approved</p>
        <p class="text-4xl font-bold text-green-600" id="profile-approved">{{ $approved }}</p>
        <p class="text-xs text-gray-500 mt-2">This month</p>
       </div>
       <div class="card-modern p-6 shadow-lg border-l-4 border-red-600">
        <p class="text-gray-600 text-sm">Requests Rejected</p>
        <p class="text-4xl font-bold text-red-600" id="profile-rejected"> {{ $rejected }}</p>
        <p class="text-xs text-gray-500 mt-2">This month</p>
       </div>
       <div class="card-modern p-6 shadow-lg border-l-4 border-blue-600">
        <p class="text-gray-600 text-sm">Average Response Time</p>
        <p class="text-4xl font-bold text-blue-600" id="profile-avg-response">{{ $avgHours }}</p>
        <p class="text-xs text-gray-500 mt-2">Hours</p>
       </div>
      </div>
     </div>
    </div>

<!-- APPROVAL/REJECTION MODAL (COUNCILOR) -->
   <div id="approval-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-6 z-50">
    <div class="card-modern w-full max-w-lg mx-auto animate-scale-in">
     <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white flex justify-between items-center rounded-t-lg">
      <h2 class="text-2xl font-bold">Review Request</h2>
      <button onclick="closeApprovalModal()" class="text-white hover:opacity-80 text-2xl">×</button>
     </div>
     <div class="p-6 space-y-4">
      <div id="approval-request-details"></div>
      <div><label class="block text-gray-700 font-semibold mb-2">Action</label>
       <div class="space-y-2"><button type="button" onclick="confirmApprove()" class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-lg font-bold hover:shadow-lg transition">✓ Approve Request</button>
        <button onclick="toggleRejectForm()" class="w-full px-4 py-3 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg font-bold hover:shadow-lg transition">✗ Reject Request</button>
       </div>
      </div>
      <div id="reject-form" class="hidden space-y-3 pt-4 border-t">
        <textarea id="rejection-reason" placeholder="Reason for rejection" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition" required></textarea>
       <div class="flex gap-2"><button type="button" onclick="toggleRejectForm()" class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">Cancel</button> 
       <button type="button" onclick="confirmReject()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition">Confirm Reject</button>
       </div>
      </div>
     </div>
    </div>
   </div>
  <!-- LETTER PREVIEW MODAL (COUNCILOR) -->
   <div id="letter-preview-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-6 z-50 overflow-auto">
    <div class="card-modern max-w-2xl w-full my-8 animate-scale-in">
     <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white flex justify-between items-center rounded-t-lg">
      <h2 class="text-2xl font-bold" >Letter Preview</h2><button onclick="closeLetterPreviewModal()" class="text-white hover:opacity-80 text-2xl">×</button>
     </div>
     <div id="letter-preview-content" class="p-6">
      <div id="preview-letter-display" class="letter-template"></div><button onclick="closeLetterPreviewModal()" class="w-full mt-6 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">Close</button>
     </div>
    </div>
   </div>
  </div>
  <!-- PROFESSIONAL SIGNING INTERFACE -->
<div id="signing-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-6 z-50 overflow-auto">
  
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl mx-auto my-4 md:my-8 animate-scale-in">

    <!-- HEADER -->
    <div class="px-8 py-6 bg-gradient-to-r from-purple-600 via-pink-600 to-blue-600 text-white flex justify-between items-center rounded-t-2xl">
      <div>
        <h2 class="text-3xl font-bold">📜 Final Approval & Digital Signature</h2>
        <p class="text-purple-100 mt-1">Review, sign, and seal the official letter</p>
      </div>
      <button onclick="closeSigningModal()" class="text-white hover:opacity-80 text-3xl">×</button>
    </div>

    <!-- BODY -->
    <div class="p-8 space-y-6">

      <!-- INFO -->
      <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <p class="text-blue-900 font-semibold">📋 You are approving for:</p>
        <p class="text-blue-800 mt-1">
          <strong id="sig-resident-name">Loading...</strong>
        </p>
      </div>

      <!-- GRID -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- LEFT: LETTER -->
        <div class="md:col-span-2 space-y-4">
          <h3 class="text-xl font-bold text-gray-800">📄 Letter Preview</h3>
          <div id="signing-letter-preview"
               class="letter-template max-h-96 overflow-y-auto border-2 border-gray-200 rounded-lg p-4 bg-gray-50 shadow-inner">
          </div>
        </div>

        <!-- RIGHT: TOOLS -->
        <div class="space-y-4">

          <!-- SIGNATURE -->
          <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-lg border-2 border-purple-200">
            <label class="block text-lg font-bold text-gray-800 mb-3">
              ✍️ Your Signature
            </label>

            <canvas id="signature-canvas" width="280" height="120"
              class="w-full border-2 border-purple-300 rounded bg-white cursor-crosshair hover:shadow-lg transition">
            </canvas>

            <p class="text-xs text-gray-600 mt-2">Draw your signature above</p>

            <button onclick="clearSignature()"
              class="w-full mt-2 px-3 py-2 text-sm bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition font-semibold">
              🔄 Clear Signature
            </button>
          </div>

          <!-- STAMP SECTION -->
          <div class="bg-gradient-to-br from-pink-50 to-orange-50 p-4 rounded-lg border-2 border-pink-200">
            <label class="block text-lg font-bold text-gray-800 mb-3">
              🔐 Official Stamp
            </label>

            <!-- DEFAULT STAMP PREVIEW -->
            <div class="text-center p-3 bg-white rounded border relative overflow-hidden">
              <img src="/images/default-stamp.png"
                   class="mx-auto w-24 opacity-70 rotate-[-12deg]" />

              <p class="text-xs text-gray-600 font-semibold mt-2">
                Default Stamp (Auto Used)
              </p>
            </div>

            <!-- UPLOAD OPTION -->
            <div class="mt-4">
              <label class="block text-sm font-semibold text-gray-700 mb-1">
                Upload Custom Stamp (optional)
              </label>

              <input type="file"
                     id="stamp-upload"
                     accept="image/*"
                     class="w-full text-sm border rounded p-2 bg-white">

              <p class="text-xs text-gray-500 mt-1">
                If not uploaded, default official stamp will be used
              </p>
            </div>

            <!-- LIVE PREVIEW -->
            <div id="stamp-preview" class="mt-4 hidden text-center">
              <p class="text-xs text-gray-600 mb-1">Preview:</p>
              <img id="stamp-preview-img" class="mx-auto w-24 opacity-80 rotate-[-12deg]" />
            </div>
          </div>

          <!-- WARNING -->
          <div class="bg-yellow-50 border border-yellow-300 p-3 rounded">
            <p class="text-xs text-yellow-900 font-semibold">
              ⚠️ By signing, you authorize this letter as official and legally binding.
            </p>
          </div>

        </div>
      </div>

      <!-- ACTION BUTTONS -->
      <div class="flex gap-4 pt-6 border-t">
        <button onclick="closeSigningModal()"
          class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition">
          Cancel
        </button>

        <button onclick="finalizeApprovalWithSignature()"
          class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-teal-500 text-white rounded-lg font-bold hover:shadow-lg transition transform hover:scale-105">
          ✓ Sign & Approve
        </button>
      </div>

    </div>
  </div>
</div>


<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
     const requests = @json($requests);
let statusChart, typesChart, trendsChart;

function updateCharts() {
      const pending = allRequests.filter(r => r.status === 'Pending').length;
      const approved = allRequests.filter(r => r.status === 'Approved').length;
      const rejected = allRequests.filter(r => r.status === 'Rejected').length;

      // Status Distribution Chart
      const statusCtx = document.getElementById('statusChart');
      if (statusChart) statusChart.destroy();
      statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
          labels: ['Pending', 'Approved', 'Rejected'],
          datasets: [{
            data: [pending, approved, rejected],
            backgroundColor: ['#fbbf24', '#4ade80', '#f87171'],
            borderColor: ['#f59e0b', '#22c55e', '#ef4444'],
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: { padding: 15, font: { size: 12, weight: 'bold' } }
            }
          }
        }
      });

      //purpose_type Chart
      const types = {};
      allRequests.forEach(r => {
        types[r.purpose_type ] = (types[r.purpose_type ] || 0) + 1;
      });

      const typesCtx = document.getElementById('typesChart');
      if (typesChart) typesChart.destroy();
      typesChart = new Chart(typesCtx, {
        type: 'bar',
        data: {
          labels: Object.keys(types),
          datasets: [{
            label: 'Requests',
            data: Object.values(types),
            backgroundColor: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            borderColor: '#667eea',
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          indexAxis: 'y',
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: { beginAtZero: true }
          }
        }
      });

      // Trends Chart
      const trendsCtx = document.getElementById('trendsChart');
      if (trendsChart) trendsChart.destroy();
      
      const last7Days = {};
      for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateStr = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        last7Days[dateStr] = 0;
      }

      allRequests.forEach(r => {
        const dateStr = new Date(r.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        if (dateStr in last7Days) last7Days[dateStr]++;
      });

      trendsChart = new Chart(trendsCtx, {
        type: 'line',
        data: {
          labels: Object.keys(last7Days),
          datasets: [{
            label: 'Daily Requests',
            data: Object.values(last7Days),
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: '#667eea',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    }

 

// LOAD ON PAGE
document.addEventListener('DOMContentLoaded', () => {
    updateCharts();
});
</script>
<script>
   
    const allRequests = @json($requests);
    let filteredRequests = allRequests;
    let currentSelectedRequest = null;
    let currentSignaturePad = null;

let currentSignatureData = null;
    
    // Simple tab switcher
    function switchCouncilorTab(tab) {
        ['dashboard','requests','reports','profile'].forEach(t=>{
            document.getElementById(t+'-tab').classList.add('hidden');
            document.getElementById('tab-'+t).classList.remove('border-white');
            document.getElementById('tab-'+t).classList.add('border-transparent');
        });
        document.getElementById(tab+'-tab').classList.remove('hidden');
        document.getElementById('tab-'+tab).classList.add('border-white');
        document.getElementById('tab-'+tab).classList.remove('border-transparent');
    }

  

     

     
   


     function handleCouncilorLogout() {
      currentCouncilor = null;
      showToast('Logged out successfully', 'info');
      window.location.href = "{{ route('home') }}";

    }

    // Example Chart Data
    

     function updateCouncilorDashboard() {
      const stats = {
        total: filteredRequests.length,
        pending: filteredRequests.filter(r => r.status === 'Pending').length,
        approved: filteredRequests.filter(r => r.status === 'Approved').length,
        rejected: filteredRequests.filter(r => r.status === 'Rejected').length
      };
      document.getElementById('stat-total').textContent = stats.total;
      document.getElementById('stat-pending').textContent = stats.pending;
      document.getElementById('stat-approved').textContent = stats.approved;
      document.getElementById('stat-rejected').textContent = stats.rejected;
    }

     

    function openApprovalModal(id) {

    // Find full request object
    const request = allRequests.find(r => r.id === id);

    if (!request) return;

    currentSelectedRequest = request; // store full object

    const detailsDiv = document.getElementById('approval-request-details');

    detailsDiv.innerHTML = `
        <div class="space-y-2 mb-4 pb-4 border-b">
          <p><strong>Letter #:</strong> ${request.reference_number}</p>
          <p><strong>Resident:</strong> ${request.user?.name || 'N/A'}</p>
          <p><strong>Type:</strong> ${request.letter_type}</p>
          <p><strong>Status:</strong> ${request.status}</p>
          <p><strong>Submitted:</strong> ${formatDate(request.created_at)}</p>
        </div>
    `;

    document.getElementById('rejection-reason').value = '';
    document.getElementById('reject-form').classList.add('hidden');
    document.getElementById('approval-modal').classList.remove('hidden');
}

    function closeApprovalModal() {
      document.getElementById('approval-modal').classList.add('hidden');
      currentSelectedRequest = null;
    }

    function toggleRejectForm() {
      document.getElementById('reject-form').classList.toggle('hidden');
    }

   

    

   function openLetterPreviewModal(requestId) {
  const request = allRequests.find(r => r.id === requestId);
  if (!request) return;

  const previewDisplay = document.getElementById('preview-letter-display');

  previewDisplay.innerHTML = `
  <div class="max-w-3xl mx-auto bg-white p-10 shadow-xl rounded-lg border border-gray-200 relative">

    <!-- LETTER HEADER -->
    <div class="text-center mb-8">
      <h1 class="text-4xl font-bold text-gray-800 tracking-wide">OFFICIAL LETTER</h1>
      <p class="text-gray-500 mt-2">Community Services Department</p>
      <p class="text-gray-600">Letter Number: ${request.reference_number}</p>
    </div>

    <!-- DATE -->
    <div class="flex justify-between mb-8 text-sm text-gray-600">
      <div>
        <p><strong>Date Issued:</strong></p>
        <p>${formatDate(request.approved_at)}</p>
      </div>
    </div>

    <!-- BODY -->
    <div class="text-gray-800 leading-relaxed space-y-4 text-[15px]">

      <p class="font-semibold">TO WHOM IT MAY CONCERN,</p>

      <p>
        This letter serves as official confirmation that 
        <strong>${request.user?.name || 'N/A'}</strong> 
        is a registered resident within our community.
      </p>

      <p>The details of the resident are as follows:</p>

      <div class="bg-gray-50 border rounded-lg p-4">
        <p><strong>Full Name:</strong> ${request.user?.name || 'N/A'}</p>
        <p><strong>Email Address:</strong> ${request.user?.email || 'N/A'}</p>
        <p><strong>Current Address:</strong><br>
${request.address ? formatAddress(request.address) : 'N/A'}
</p>
        
        <p><strong>Letter Type:</strong> ${request.letter_type || 'N/A'}</p>
        <p><strong>Reference Number:</strong> ${request.reference_number}</p>
      </div>

      <p>
        This letter is issued upon request and may be used for official purposes where proof of residency is required.
      </p>

      <p class="mt-6">Yours faithfully,</p>
    </div>

    <!-- SIGNATURE + STAMP SECTION -->
<div class="mt-16 relative">

  <!-- STAMP (LEFT SIDE) -->
  ${request.stamp ? `
    <img 
  src="${request.stamp}" 
  class="absolute left-10 bottom-0 w-32 opacity-70 rotate-[-15deg]"
  style="mix-blend-mode:multiply; filter:contrast(1.2);"
/>
  ` : ''}

  <!-- SIGNATURE (RIGHT SIDE) -->
  <div class="flex justify-end">
    
   <div class="text-right">

  <!-- ALWAYS SHOW SIGNATURE AREA -->
  

  <!-- SHOW BUTTON ONLY IF NOT SIGNED -->
  ${request.status === 'Approved' && !request.signed_letter ? `
    <button onclick="openSigningModal(${request.id})"
      class="mt-3 px-6 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
      ✍️ Sign Letter
    </button>
  ` : ''}

     ${request.signed_letter ? `
  <img 
    src="${request.signed_letter}?t=${Date.now()}" 
    class="h-20 mt-2 ml-auto"
  />
` : ''} 

  <p class="font-bold mt-2">Community Councilor</p>
  <p class="text-sm text-gray-500">${request.approved_by || ''}</p>

</div>

  </div>

</div>
  `;

  document.getElementById('letter-preview-modal').classList.remove('hidden');

 
}
function formatAddress(address) {
  if (!address) return 'N/A';

  // Split long OpenStreetMap address into readable parts
  return address
    .split(',')
    .map(part => part.trim())
    .filter(part => part.length > 0)
    .join('<br>');
}

function openSigningModal(requestId) {
    const request = allRequests.find(r => r.id === requestId);
    if (!request) return;

    currentSelectedRequest = request;

    document.getElementById('letter-preview-modal').classList.add('hidden');
    document.getElementById('signing-modal').classList.remove('hidden');

    // FORCE proper init
    setTimeout(() => {
        initSignaturePad();

        if (!currentSignaturePad) {
            alert("Signature pad failed to load");
        }
    }, 500); // increase time
}
function closeSigningModal() {
    document.getElementById('signing-modal').classList.add('hidden');

    if (currentSignaturePad) {
        currentSignaturePad.clear();
    }

    currentSelectedRequest = null;
    currentSignatureData = null;
}
function initSignaturePad() {
    const canvas = document.getElementById('signature-canvas');
    if (!canvas) return;

    const ratio = Math.max(window.devicePixelRatio || 1, 1);

    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;

    const ctx = canvas.getContext("2d");
    ctx.scale(ratio, ratio);

    currentSignaturePad = new SignaturePad(canvas, {
        minWidth: 1.5,
        maxWidth: 2.5,
        penColor: "#000"
    });

    currentSignaturePad.clear();

    // ✅ LOAD DEFAULT SIGNATURE IMAGE
    const img = new Image();
    img.src = "/images/default-signature.png";

    img.onload = function () {
        ctx.drawImage(img, 0, 0, canvas.width / ratio, canvas.height / ratio);
    };
}

function clearSignature() {
    if (currentSignaturePad) {
        currentSignaturePad.clear();
    }
}

function saveSignature() {
    if (!currentSignaturePad || currentSignaturePad.isEmpty()) {
       showToast("Please draw your signature first");
        return;
    }

    // HIGH QUALITY EXPORT
    currentSignatureData = currentSignaturePad.toDataURL("image/png");

    showToast("Signature saved successfully");

    finalizeApprovalWithSignature();
}

let signatureData;
async function finalizeApprovalWithSignature() {

    if (!currentSelectedRequest) {
    showToast("No request selected", "error");
    return;
}

   if (currentSignaturePad && !currentSignaturePad.isEmpty()) {
    // user drew new signature
    signatureData = currentSignaturePad.toDataURL("image/png");
} else {
    // ✅ use default image
    signatureData = "/images/default-signature.png";
}

   

    const formData = new FormData();
    formData.append('signature', signatureData);

    const stampInput = document.getElementById('stamp-upload');
    if (stampInput && stampInput.files.length > 0) {
        formData.append('stamp', stampInput.files[0]);
    }

    try {
        const response = await fetch(
            `/councilor/approve-with-signature/${currentSelectedRequest.id}`,
            {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                },
                body: formData
            }
        );

        // ❌ STOP if not OK
        if (!response.ok) {
            console.error("Server error:", response.status);
           showToast("Server error: " + response.status);
            return;
        }

        // ✅ ONLY parse once
        const data = await response.json();

        if (data.success) {

            applyApprovalUpdate({
                id: currentSelectedRequest.id,
                status: "Approved",
                signed_letter: data.signed_letter,
                stamp: data.stamp
            });

           showToast("Signed successfully ✅");

            closeSigningModal();

            // small delay to avoid race condition
            setTimeout(() => {
                openLetterPreviewModal(currentSelectedRequest.id);
            }, 300);

        } else {
            showToast(data.message || "Approval failed");
        }

    } catch (error) {
        // 🔥 ONLY show if REAL failure
        console.error("REAL ERROR:", error);
        showToast("Unexpected error occurred");
    }
}

function saveAndApprove() {
    if (!currentSignaturePad || currentSignaturePad.isEmpty()) {
        showToast("Please draw signature first");
        return;
    }
  console.log("Selected Request:", currentSelectedRequest);
console.log("SignaturePad:", currentSignaturePad);
    finalizeApprovalWithSignature();
}

    function closeLetterPreviewModal() {
      document.getElementById('letter-preview-modal').classList.add('hidden');
    }

    function generateReport() {
      const fromDate = document.getElementById('report-from-date').value;
      const toDate = document.getElementById('report-to-date').value;

      let reportData = allRequests;
      if (fromDate) reportData = reportData.filter(r => new Date(r.created_at) >= new Date(fromDate));
      if (toDate) {
        const toDateEnd = new Date(toDate);
        toDateEnd.setHours(23, 59, 59, 999);
        reportData = reportData.filter(r => new Date(r.created_at) <= toDateEnd);
      }

      const total = reportData.length;
      const approved = reportData.filter(r => r.status === 'Approved').length;
      const approvalRate = total > 0 ? Math.round((approved / total) * 100) : 0;
      
      let totalDays = 0;
      let completedCount = 0;
      reportData.forEach(r => {
        if (r.approved_at) {
          const days = Math.floor((new Date(r.approved_at) - new Date(r.created_at)) / (1000 * 60 * 60 * 24));
          totalDays += days;
          completedCount++;
        }
      });
      const avgTime = completedCount > 0 ? Math.round(totalDays / completedCount) : 0;

      document.getElementById('report-total-requests').textContent = total;
      document.getElementById('report-approval-rate').textContent = approvalRate + '%';
      document.getElementById('report-avg-time').textContent = avgTime + ' days';

      const detailsList = document.getElementById('report-details-list');
      if (reportData.length === 0) {
        detailsList.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No data for selected date range</td></tr>';
        return;
      }

     detailsList.innerHTML = reportData.map(request => {

    let days = 'N/A';

if (request.status === 'Approved') {

    if (request.approved_at && request.created_at) {

        const created = new Date(request.created_at);
        const approved = new Date(request.approved_at);

        const diffTime = approved - created;

        if (!isNaN(diffTime)) {
            days = Math.max(1, Math.floor(diffTime / (1000 * 60 * 60 * 24)));
        } else {
            days = '0';
        }

    } else {
        days = '0'; // fallback if approved but no approved_at
    }

} else if (request.status === 'Rejected') {
    days = 'Rejected';
} else {
    days = 'In Progress'; // instead of "Pending"
}

    const statusClass = `status-${(request.status || '').toLowerCase()}`;

    return `
      <tr class="border-b hover:bg-gray-50 transition">
        <td class="px-6 py-4 font-semibold text-gray-800">
            ${request.reference_number || 'N/A'}
        </td>

        <td class="px-6 py-4 text-gray-700">
            ${request.user?.name || 'N/A'}
        </td>

        <td class="px-6 py-4 text-gray-700">
            ${request.letter_type || 'N/A'}
        </td>

        <td class="px-6 py-4">
            <span class="status-badge ${statusClass}">
                ${request.status || 'N/A'}
            </span>
        </td>

        <td class="px-6 py-4 text-gray-700">
            ${formatDate(request.created_at)}
        </td>

        <td class="px-6 py-4 text-gray-700 font-semibold">
            ${days}
        </td>
        <td class="px-6 py-4">
    <form method="POST" action="/councilor/request/delete/${request.id}"
        onsubmit="return confirm('Are you sure you want to delete this request? This cannot be undone!')">
       <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">

        <button type="submit"
            class="text-red-600 font-semibold hover:underline">
            Delete
        </button>
    </form>
</td>
      </tr>
    `;
}).join('');
    }

    function exportReportPDF() {
      const element = document.getElementById('reports-tab');
      const opt = {
        margin: 10,
        filename: 'Community_Letter_Report_' + new Date().toISOString().slice(0, 10) + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
      };
      html2pdf().set(opt).from(element).save();
      showToast('Report exported as PDF!', 'success');
    }

   function exportReportCSV() {
    let csv = 'Letter #,Resident,Email,Type,Status,Submitted,Approved By\n';

    allRequests.forEach(r => {

        const letterNumber = r.reference_number || 'N/A';
        const name = r.user?.name || 'N/A';
        const email = r.user?.email || 'N/A';
        const type = r.letter_type || 'N/A';
        const status = r.status || 'N/A';
        const submitted = r.created_at || 'N/A';
        const approvedBy = r.approved_by || 'N/A';

        csv += `"${letterNumber}","${name}","${email}","${type}","${status}","${submitted}","${approvedBy}"\n`;
    });

    const link = document.createElement('a');
    link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    link.download = 'Community_Letter_Report_' + new Date().toISOString().slice(0, 10) + '.csv';
    link.click();

    showToast('Report exported as CSV!', 'success');
}

    function updateProfileTab() {
      const total = allRequests.length;
      const approved = allRequests.filter(r => r.status === 'Approved').length;
      const rejected = allRequests.filter(r => r.status === 'Rejected').length;
      
      document.getElementById('profile-total-managed').textContent = total;
      document.getElementById('profile-approved').textContent = approved;
      document.getElementById('profile-rejected').textContent = rejected;
    }

    function updateAllDashboards() {
      updateResidentDashboard();
      renderResidentRequests();
      updateCouncilorDashboard();
      renderCouncilorRequests();
    }

    document.addEventListener('click', (e) => {
      const requestModal = document.getElementById('request-modal');
      const reviewModal = document.getElementById('review-modal');
      const letterModal = document.getElementById('letter-modal');
      const approvalModal = document.getElementById('approval-modal');
      const previewModal = document.getElementById('letter-preview-modal');

      if (e.target === requestModal) closeRequestModal();
      if (e.target === reviewModal) closeReviewModal();
      if (e.target === letterModal) closeLetterModal();
      if (e.target === approvalModal) closeApprovalModal();
      if (e.target === previewModal) closeLetterPreviewModal();
    });

// started

async function confirmApprove() {
    if (!currentSelectedRequest) return;

    const response = await fetch(`/councilor/approve/${currentSelectedRequest.id}`, {
    method: 'POST',
     credentials: "same-origin", // 🔥 ADD THIS
    headers: {
      
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
         'X-Requested-With': 'XMLHttpRequest', // 🔥 ADD THIS
        'Accept': 'application/json'
    }
});

    const data = await response.json();

   if (data.success) {

    showToast('Approved successfully');

    // 🔥 UPDATE LOCAL DATA
    const index = allRequests.findIndex(r => r.id === currentSelectedRequest.id);

    if (index !== -1) {
        allRequests[index].status = "Approved";
        allRequests[index].approved_at = new Date().toISOString();
        allRequests[index].approved_by = "Councilor";
    }

    // 🔥 UPDATE FILTERED DATA
    const fIndex = filteredRequests.findIndex(r => r.id === currentSelectedRequest.id);

    if (fIndex !== -1) {
        filteredRequests[fIndex].status = "Approved";
        filteredRequests[fIndex].approved_at = new Date().toISOString();
        filteredRequests[fIndex].approved_by = "Councilor";
    }

    // 🔥 RE-RENDER TABLE
    renderCouncilorRequests();
    updateCharts();
    updateCouncilorDashboard();

    // close modal
    closeApprovalModal();
}
}


async function confirmReject() {
    const reason = document.getElementById('rejection-reason').value;

    if (!reason) {
        alert('Enter rejection reason');
        return;
    }

    const response = await fetch(`/councilor/reject/${currentSelectedRequest.id}`, {
    method: 'POST',
     credentials: "same-origin",
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
         'X-Requested-With': 'XMLHttpRequest', // 🔥 ADD THIS
        
        'Accept': 'application/json'
    },
    body: JSON.stringify({ reason })
});

    const data = await response.json();

    if (data.success) {
        showToast('Rejected successfully');
        location.reload();
    }
}
 
function formatDate(dateString) {
    if (!dateString) return 'N/A';

    const date = new Date(dateString);

    if (isNaN(date)) return 'N/A';

    return date.toLocaleDateString();
}

function renderCouncilorRequests() {
    const requestsList = document.getElementById('councilor-requests-list');

    if (!filteredRequests || filteredRequests.length === 0) {
        requestsList.innerHTML =
            '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No requests found</td></tr>';
        return;
    }

    requestsList.innerHTML = filteredRequests.map(request => {

        const statusClass = `status-${(request.status || '').toLowerCase()}`;

        return `
        <tr class="border-b hover:bg-gray-50 transition">
            <td class="px-6 py-4 font-semibold text-gray-800">
                ${request.reference_number || 'N/A'}
            </td>

            <td class="px-6 py-4 text-gray-700">
                ${request.user?.name || 'N/A'}
            </td>

            <td class="px-6 py-4 text-gray-700">
                ${request.letter_type || 'N/A'}
            </td>

            <td class="px-6 py-4">
                <span class="status-badge ${statusClass}">
                    ${request.status || 'N/A'}
                </span>
            </td>

            <td class="px-6 py-4 flex gap-3">

                ${request.status === 'Pending' 
                    ? `<button onclick="openApprovalModal(${request.id})"
                        class="text-purple-600 hover:text-purple-800 font-semibold">
                        Review
                       </button>` 
                    : ''}

                ${request.status === 'Approved' 
                    ? `<button onclick="openLetterPreviewModal(${request.id})"
                        class="text-blue-600 hover:text-blue-800 font-semibold">
                        View Letter
                       </button>` 
                    : ''}

                ${request.status === 'Rejected'
                    ? `<span class="text-red-600 font-semibold">Rejected</span>`
                    : ''}

            </td>
        </tr>
        `;
    }).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    filteredRequests = allRequests;
    renderCouncilorRequests();
});


document.getElementById('stamp-upload').addEventListener('change', function (e) {
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (event) {
            document.getElementById('stamp-preview-img').src = event.target.result;
            document.getElementById('stamp-preview').classList.remove('hidden');
        };

        reader.readAsDataURL(file);
    }
});



function applyApprovalUpdate(updatedRequest) {

    // update main array
    const index = allRequests.findIndex(r => r.id === updatedRequest.id);

    if (index !== -1) {
        allRequests[index] = {
            ...allRequests[index],
            ...updatedRequest
        };
    }

    // update filtered array too
    const fIndex = filteredRequests.findIndex(r => r.id === updatedRequest.id);

    if (fIndex !== -1) {
        filteredRequests[fIndex] = {
            ...filteredRequests[fIndex],
            ...updatedRequest
        };
    }

    // refresh UI
    renderCouncilorRequests();
    updateCharts();
    updateCouncilorDashboard();
}

function applyFilters() {
    const searchLetter = document.querySelector('input[name="search"]').value.toUpperCase();
    const fromDate = document.querySelector('input[name="from_date"]').value;
    const toDate = document.querySelector('input[name="to_date"]').value;

    filteredRequests = allRequests.filter(request => {

        let matches = true;

        // FIXED: reference_number (not letter_number)
        if (searchLetter) {
            matches = matches &&
                (request.reference_number || '')
                .toUpperCase()
                .includes(searchLetter);
        }

        if (fromDate) {
            matches = matches &&
                new Date(request.created_at) >= new Date(fromDate);
        }

        if (toDate) {
            const toDateEnd = new Date(toDate);
            toDateEnd.setHours(23, 59, 59, 999);

            matches = matches &&
                new Date(request.created_at) <= toDateEnd;
        }

        return matches;
    });

    renderCouncilorRequests();
    updateCouncilorDashboard();

    showToast('Filters applied', 'info');
}

function resetFilters() {

    document.querySelector('input[name="search"]').value = '';
    document.querySelector('input[name="from_date"]').value = '';
    document.querySelector('input[name="to_date"]').value = '';

    filteredRequests = [...allRequests];

    renderCouncilorRequests();
    updateCouncilorDashboard();

    showToast('Filters reset', 'info');
}

function resetReportFilters() {
    document.getElementById('report-from-date').value = '';
    document.getElementById('report-to-date').value = '';

    generateReport();

    showToast('All filters cleared — showing full report', 'info');
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');

    toast.className = `notification-toast toast-${type}`;
    toast.textContent = message;

    // Optional: prevent too many toasts stacking
    const existingToasts = document.querySelectorAll('.notification-toast');
    existingToasts.forEach(t => t.remove());

    document.body.appendChild(toast);

    // Smooth fade out before removal
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = '0.4s ease';
    }, 2500);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>



</body>
</html>