<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Resident Dashboard</title>
<script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
 



<style>
/* ---------------------------- CSS ---------------------------- */
* { box-sizing: border-box; }
html, body { height: 100%; margin: 0; padding: 0; font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

.gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.gradient-secondary { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.card-modern { background: white; border-radius: 20px; box-shadow:0 10px 30px rgba(0,0,0,.08); transition: all 0.3s ease; }
.card-modern:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,.12); }

.status-badge { display: inline-block; padding: 6px 14px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.status-pending { background: #fef3c7; color: #92400e; }
.status-approved { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #7f1d1d; }
.status-completed { background: #d1fae5; color: #065f46; }

.card-gradient { border-radius: 20px; position: relative; overflow: hidden; box-shadow:0 10px 30px rgba(0,0,0,.12); }
.card-gradient::before { content:''; position:absolute; top:0; left:0; right:0; bottom:0; background:radial-gradient(circle at top right, rgba(255,255,255,.3), transparent); z-index:1; }

.animate-fade-in { animation: fadeIn 0.6s ease-out; }
.animate-slide-in-up { animation: slideInUp 0.6s ease-out; }
.animate-scale-in { animation: scaleIn 0.5s ease-out; }
#letter-export {
  background: white !important;
}
#letter-export img {
  max-width: 100%;
  display: block;
}
@keyframes fadeIn { from {opacity:0; transform:translateY(20px);} to {opacity:1; transform:translateY(0);} }
@keyframes slideInUp { from {opacity:0; transform:translateY(40px);} to {opacity:1; transform:translateY(0);} }
@keyframes scaleIn { from {opacity:0; transform:scale(0.95);} to {opacity:1; transform:scale(1);} }

</style>
</head>
<body class="bg-gray-50 h-full w-full overflow-auto">

<!-- ================= RESIDENT DASHBOARD ================= -->
<div id="resident-dashboard-page" class="min-h-full w-full ">

  <!-- Dashboard Header -->
  <div class="w-full gradient-primary text-white py-8 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
      <div>
        <h1 class="text-3xl font-bold">Welcome, <span>{{ auth()->user()->name }}</span>!</h1>
        <p class="opacity-90 mt-1">Manage your letter requests</p>
      </div>
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

  <!-- Dashboard Content -->
  <div class="max-w-7xl mx-auto px-6 py-12">

    <!-- Action Buttons -->
    
    <div class="grid md:grid-cols-3 gap-6 mb-12">
        
      <button onclick="openRequestModal()" class="card-modern p-8 text-center hover:shadow-xl border-t-4 border-purple-600">
       <div class="text-5xl mb-4">
        📝
       </div><h3 class="text-xl font-bold text-gray-800">New Request</h3>
       <p class="text-gray-600 mt-2">Submit a new letter request</p>
    </button>


      <div class="card-modern p-8 text-center border-t-4 border-yellow-500">
        <div class="text-5xl mb-4">⏱️</div>
        <h3 class="text-xl font-bold text-gray-800" id="resident-pending-count">{{ $requests->where('status','Pending')->count() }}</h3>
        <p class="text-gray-600 mt-2">Pending Requests</p>
      </div>

      <div class="card-modern p-8 text-center border-t-4 border-green-500">
        <div class="text-5xl mb-4">✅</div>
        <h3 class="text-xl font-bold text-gray-800" id="resident-completed-count">{{ $requests->where('status','Approved')->count() }}</h3>
        <p class="text-gray-600 mt-2">Completed Requests</p>
      </div>
    </div>


    <!-- Requests Table -->
    <div class="card-modern overflow-hidden">

  <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white">
    <h2 class="text-2xl font-bold">Your Letter Requests</h2>
  </div>

  <div id="resident-requests-container" class="overflow-x-auto">
    <table class="w-full">

      <thead>
        <tr class="bg-gray-100 border-b">
          <th class="px-6 py-3 text-left font-semibold text-gray-700">Letter #</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-700">Type</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-700">Date</th>
          <th class="px-6 py-3 text-left font-semibold text-gray-700">Actions</th>
           <th class="px-6 py-3 text-left font-semibold text-gray-700">Cancel</th>
        </tr>
      </thead>

      <!-- ✅ ONLY ONE TBODY -->
      <tbody>

        @if($requests->isEmpty())
        <tr>
          <td colspan="5" class="px-6 py-8 text-center text-gray-500">
            No requests yet. Click "New Request" to get started!
          </td>
        </tr>
        @else

        @foreach($requests as $request)
        <tr class="border-b hover:bg-gray-50">

          <td class="px-6 py-4 font-semibold">
            {{ $request->reference_number }}
          </td>

          <td class="px-6 py-4">
            {{ $request->letter_type }}
          </td>

          <td class="px-6 py-4">
            <span class="status-badge status-{{ strtolower($request->status) }}">
              {{ $request->status }}
            </span>
          </td>

          <td class="px-6 py-4">
            {{ $request->created_at->format('d M Y') }}
          </td>

          <td class="px-6 py-4">
            <button 
                onclick="openReviewModal({{ $request->id }})" 
                class="text-purple-600 font-semibold hover:underline">
                   View
            </button>
          </td>
          
          <td class="px-6 py-4 flex gap-3 items-center">

    <button 
        onclick="openReviewModal({{ $request->id }})" 
        class="text-purple-600 font-semibold hover:underline">
        View
    </button>

    @if($request->status == 'Pending')
        <button 
            type="button"
            onclick="openCancelModal({{ $request->id }})"
            class="text-yellow-600 font-semibold hover:underline">
            Cancel
        </button>
    @endif

</td>

        </tr>
        @endforeach

        @endif

      </tbody>

    </table>
  </div>

</div>


   <!-- REQUEST MODAL (RESIDENT) -->
    <!-- CANCEL CONFIRM MODAL -->
<div id="cancel-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-6 z-50">

  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-scale-in overflow-hidden">

    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4">
      <h2 class="text-xl font-bold">Cancel Request</h2>
    </div>

    <!-- Body -->
    <div class="p-6">
      <p class="text-gray-700 text-sm">
        Are you sure you want to cancel this request? This action cannot be undone.
      </p>

      <div class="flex items-center gap-2 mt-4 text-red-600 text-sm">
        ⚠️ Your request will be removed from pending processing.
      </div>
    </div>

    <!-- Footer -->
    <div class="flex gap-3 p-6 pt-0">

      <button 
        onclick="closeCancelModal()"
        class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-100 transition">
        No, Keep It
      </button>

      <form id="cancelForm" method="POST" class="flex-1">
        @csrf
        <button 
          type="submit"
          class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
          Yes, Cancel
        </button>
      </form>

    </div>

  </div>
</div>

<div id="request-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-6 z-50">
  <div class="card-modern max-w-md w-full animate-scale-in">

    <!-- HEADER -->
    <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white flex justify-between items-center rounded-t-lg">
      <h2 class="text-2xl font-bold">New Letter Request</h2>
      <button onclick="closeRequestModal()" class="text-white hover:opacity-80 text-2xl">×</button>
    </div>

    <!-- FORM -->
    <form id="requestForm" method="POST" action="{{ route('resident.request.store') }}" class="p-6 space-y-4">
      @csrf

      <!-- Request Type -->
      <div>
        <label class="block text-gray-700 font-semibold mb-2">Request Type</label>
        <select name="letter_type" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
          <option value="">Select a type...</option>
          <option value="Proof of Residence">Proof of Residence</option>
         
        </select>
      </div>

      <!-- Purpose (Dropdown) -->
      <div>
        <label class="block text-gray-700 font-semibold mb-2">Purpose</label>
        <select name="purpose_type" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
          <option value="">Select purpose...</option>
          <option value="Bank Account">Bank Account</option>
          <option value="School Registration">School Registration</option>
          <option value="Job Application">Job Application</option>
          <option value="Government Use">Government Use</option>
        </select>
      </div>

      <div>
  <label class="block text-gray-700 font-semibold mb-2">South African ID Number</label>
  <input type="text" id="id_number" required
         placeholder="Enter your 13-digit SA ID number"
         class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">

         <p id="id_error" class="text-sm text-red-600 mt-1 hidden"></p>
</div>

     <div>
  <label class="block text-gray-700 font-semibold mb-2">Detected Current Address</label>

  <input type="text" id="auto_address" readonly
         class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg bg-gray-100"
         placeholder="Detecting your location..." />
</div>
      <!-- Address Accuracy -->
     
      <input type="hidden" name="address" id="address">
      <!-- Hidden GPS Fields -->
      <input type="hidden" name="latitude" id="latitude">
      <input type="hidden" name="longitude" id="longitude">

      <!-- Declaration -->
      <div class="flex items-start gap-2">
        <input type="checkbox" name="declaration" value="1" required>
        <label class="text-sm text-gray-600">
          I confirm that the information provided is true and correct
        </label>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-4">
        <button type="button" onclick="closeRequestModal()" class="flex-1 px-4 py-2 border rounded-lg">
          Cancel
        </button>

        <button type="submit" class="flex-1 px-4 py-2 gradient-primary text-white rounded-lg">
          Submit Request
        </button>
      </div>

    </form>
  </div>
</div>

  </div>

</div>


<!-- REVIEW MODAL (RESIDENT) -->
   <div id="review-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-6 z-50 overflow-auto">
    <div class="card-modern max-w-md w-full my-8 animate-scale-in">
     <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white flex justify-between items-center rounded-t-lg">
      <h2 class="text-2xl font-bold">Request Details</h2><button onclick="closeReviewModal()" class="text-white hover:opacity-80 text-2xl">×</button>
     </div>
     <div id="review-content" class="p-6 space-y-4"></div>
    </div>
   </div><!-- LETTER VIEW MODAL (RESIDENT) -->
   <div id="letter-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-6 z-50 overflow-auto">
    <div class="card-modern max-w-2xl w-full my-8 animate-scale-in">
     <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white flex justify-between items-center rounded-t-lg">
      <h2 class="text-2xl font-bold">Your Letter</h2><button onclick="closeLetterModal()" class="text-white hover:opacity-80 text-2xl">×</button>
     </div>
     <div id="letter-content" class="p-6">
      <div id="letter-display" class="letter-template mb-6"></div>
      <div class="flex gap-3"><button onclick="downloadLetter()" class="flex-1 px-4 py-2 gradient-primary text-white rounded-lg font-semibold hover:shadow-lg transition">Download PDF</button> 
      <button onclick="closeLetterModal()" class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">Close</button>
      </div>
</div>
</div>

<script>
// ===== VALIDATION FUNCTION =====
function validateSAIDLive(id) {
    if (id.length === 0) return null;

    if (!/^\d*$/.test(id)) {
        return "Only numbers are allowed.";
    }

    if (id.length < 13 && id.length > 0) {
    return null; // don't show error yet while typing
}

    if (id.length > 13) {
        return "Too many digits (max is 13).";
    }

    if (id.length === 13) {
        const month = parseInt(id.substring(2, 4));
        const day = parseInt(id.substring(4, 6));

        if (month < 1 || month > 12) {
            return "Invalid birth month in ID.";
        }

        if (day < 1 || day > 31) {
            return "Invalid birth day in ID.";
        }
    }

    return null;
}

// ===== ELEMENTS =====
const requestForm = document.getElementById("requestForm");
const idInput = document.getElementById("id_number");
const errorText = document.getElementById("id_error");

// ===== LIVE VALIDATION =====
if (idInput) {
    idInput.addEventListener("input", function () {

        const value = idInput.value.trim();
        const error = validateSAIDLive(value);

        if (error) {
            errorText.textContent = error;
            errorText.classList.remove("hidden");

            idInput.classList.add("border-red-500");
            idInput.classList.remove("border-gray-300", "border-green-500");

        } else {
            errorText.classList.add("hidden");

            idInput.classList.remove("border-red-500");

            if (value.length === 13) {
                idInput.classList.add("border-green-500");
            } else {
                idInput.classList.remove("border-green-500");
                idInput.classList.add("border-gray-300");
            }
        }
    });
}

// ===== SUBMIT VALIDATION =====
if (requestForm) {
    requestForm.addEventListener("submit", function (e) {

        const value = idInput.value.trim();
        const error = validateSAIDLive(value);

        if (error || value.length !== 13) {
            e.preventDefault();

            errorText.textContent = error || "Please enter a valid 13-digit ID number.";
            errorText.classList.remove("hidden");

            idInput.classList.add("border-red-500");
            idInput.focus();
        }
    });
}          



function goToPage(page) {
  const pages = ['resident-dashboard-page'];
  pages.forEach(p => document.getElementById(p).classList.add('hidden'));
  document.getElementById(page).classList.remove('hidden');
}

// ---------------- Resident Functions ----------------
function handleResidentLogout() {
    currentResident = null;

    showToast('Logged out successfully', 'info');

    setTimeout(() => {
        window.location.href = "{{ route('home') }}";
    }, 1200); // give toast time to show
}

function openRequestModal() {
  document.getElementById('request-modal').classList.remove('hidden');
}

 function closeRequestModal() {
      document.getElementById('request-modal').classList.add('hidden');
      
    }

    

    

    function closeReviewModal() {
      document.getElementById('review-modal').classList.add('hidden');
    }

 function openLetterModal(requestId) {

  const request = allRequests.find(r => r.id === requestId);
  if (!request) return;

  const letterDisplay = document.getElementById('letter-display');

  // ✅ Use reusable template function
  letterDisplay.innerHTML = buildLetter(request);

  // open modal
  document.getElementById('letter-modal').classList.remove('hidden');
}


function buildLetter(request) {
  return `
  <div id="letter-export" class="max-w-3xl mx-auto bg-white p-10 shadow-xl rounded-lg border border-gray-200 relative">

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
        <p>${formatDate(request.approved_at || request.created_at)}</p>
      </div>
    </div>

    <!-- BODY -->
    <div class="text-gray-800 leading-relaxed space-y-4 text-[15px]">

      <p class="font-semibold">TO WHOM IT MAY CONCERN,</p>

      <p>
        This letter confirms that 
        <strong>${request.user?.name || 'N/A'}</strong> 
        is a registered resident within the community.
      </p>

      <div class="bg-gray-50 border rounded-lg p-4">
        <p><strong>Full Name:</strong> ${request.user?.name || 'N/A'}</p>
        <p><strong>Email:</strong> ${request.user?.email || 'N/A'}</p>
        <!-- ✅ ADD CURRENT ADDRESS HERE -->
 <p><strong>Current Address:</strong><br>
${request.address ? formatAddress(request.address) : 'N/A'}
</p>
        <p><strong>Letter Type:</strong> ${request.letter_type}</p>
        <p><strong>Reference:</strong> ${request.reference_number}</p>
      </div>

      <p class="mt-4">
        This letter is issued for official use where proof of residence is required.
      </p>

      <p class="mt-6">Yours faithfully,</p>
    </div>

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
    <div class="mt-16 flex justify-between items-end">

      <div></div>

      <div class="text-right">

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
}


    function closeLetterModal() {
      document.getElementById('letter-modal').classList.add('hidden');
    }

   async function downloadLetter() {

  const element = document.getElementById('letter-export');

  if (!element) {
    showToast("Letter not found for export");
    return;
  }

  // ✅ Force browser to render updated DOM first
  await new Promise(resolve => setTimeout(resolve, 300));

  // Optional: make sure it's visible
  element.style.display = "block";

  const opt = {
    margin: 0.5,
    filename: 'official-letter.pdf',
    image: { type: 'jpeg', quality: 1 },
    html2canvas: {
      scale: 2,
      useCORS: true,
      scrollY: 0
    },
    jsPDF: {
      unit: 'in',
      format: 'a4',
      orientation: 'portrait'
    }
  };

  html2pdf()
    .set(opt)
    .from(element)
    .save();
}
    function updateResidentDashboard() {
      if (!currentResident) return;
      const pending = allRequests.filter(r => r.resident_name === currentResident.name && r.status === 'Pending').length;
      const completed = allRequests.filter(r => r.resident_name === currentResident.name && (r.status === 'Approved' || r.status === 'Completed')).length;
      document.getElementById('resident-pending-count').textContent = pending;
      document.getElementById('resident-completed-count').textContent = completed;
    }




    
  function openReviewModal(requestId) {

  const request = allRequests.find(r => r.id === requestId); // ✅ FIXED
  if (!request) return;

  const statusColor =
    request.status === 'Pending' ? 'text-yellow-600' :
    request.status === 'Approved' ? 'text-green-600' :
    'text-red-600';

  const reviewContent = document.getElementById('review-content');

  reviewContent.innerHTML = `
    <div class="space-y-3">
      <div><p class="text-sm text-gray-600">Letter Number</p><p class="text-lg font-semibold">${request.reference_number}</p></div>
      <div><p class="text-sm text-gray-600">Request Type</p><p class="text-lg font-semibold">${request.letter_type}</p></div>
      <div><p class="text-sm text-gray-600">Status</p><p class="text-lg font-semibold ${statusColor}">${request.status}</p></div>
      <div><p class="text-sm text-gray-600">Submitted</p><p class="text-lg font-semibold">${formatDate(request.created_at)}</p></div>

      ${request.status === 'Approved' ? `
        <div><p class="text-sm text-gray-600">Approved By</p><p class="text-lg font-semibold">${request.approved_by}</p></div>
        <div><p class="text-sm text-gray-600">Approved Date</p><p class="text-lg font-semibold">${formatDate(request.approved_at)}</p></div>
      ` : ''}

      ${request.rejection_reason ? `
        <div><p class="text-sm text-gray-600">Rejection Reason</p><p class="text-lg font-semibold text-red-600">${request.rejection_reason}</p></div>
      ` : ''}
    </div>

    ${request.status === 'Approved' ? `
      <button onclick="openLetterModal(${request.id})"
        class="w-full mt-4 px-4 py-2 gradient-primary text-white rounded-lg font-semibold">
        View Letter
      </button>
    ` : ''}
  `;

  document.getElementById('review-modal').classList.remove('hidden');
}
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  });
}

// Auto capture GPS location
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(
    async function(position) {

      const lat = position.coords.latitude;
      const lng = position.coords.longitude;

      document.getElementById('latitude').value = lat;
      document.getElementById('longitude').value = lng;

      try {
        const response = await fetch(
          `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
        );

        const data = await response.json();

        const address = data.display_name || "Unknown location";

        // 👇 show to user
        document.getElementById('auto_address').value = address;

        // ✅ IMPORTANT: store as STRING in hidden field
        document.getElementById('address').value = address;

      } catch (error) {
        document.getElementById('auto_address').value = "Location detected, but address not available";
        document.getElementById('address').value = "N/A";
      }

    },
    function(error) {
      document.getElementById('auto_address').value = "Location access denied";
      document.getElementById('address').value = "N/A";
    }
  );
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

function formatAddress(address) {
  if (!address) return 'N/A';

  // Split long OpenStreetMap address into readable parts
  return address
    .split(',')
    .map(part => part.trim())
    .filter(part => part.length > 0)
    .join('<br>');
}

let selectedRequestId = null;

function openCancelModal(id) {
    selectedRequestId = id;

    const form = document.getElementById('cancelForm');
    form.action = `/resident/request/${id}/cancel`; // adjust route if needed

    document.getElementById('cancel-modal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancel-modal').classList.add('hidden');
}

const allRequests = @json($allRequests);
</script>

</body>
</html>