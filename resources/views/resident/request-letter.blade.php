<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request Letter</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <script src="/_sdk/data_sdk.js"></script>
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
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

</div><!-- REQUEST MODAL (RESIDENT) -->
   <div id="request-modal" class=" fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-6 z-50">
    <div class="card-modern max-w-md w-full animate-scale-in">
     <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white flex justify-between items-center rounded-t-lg">
      <h2 class="text-2xl font-bold">New Letter Request</h2>
      <button onclick="closeRequestModal()" class="text-white hover:opacity-80 text-2xl">×</button>
     </div>
     <form onsubmit="handleNewRequest(event)" class="p-6 space-y-4">
      <div><label class="block text-gray-700 font-semibold mb-2">Request Type</label> 
      <select id="request-type" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 transition"> 
        <option value="">Select a type...</option>
         <option value="Proof of Residence">Proof of Residence</option>
          <option value="Residency Certificate">Residency Certificate</option>
           <option value="Character Certificate">Character Certificate</option>
            <option value="Income Certificate">Income Certificate</option> 
            <option value="No Objection Certificate">No Objection Certificate</option>
         </select>
      </div>
      <div class="flex gap-3 pt-4"><button type="button" onclick="closeRequestModal()" class="flex-1 px-4 py-2 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">Cancel</button> 
      <button type="submit" class="flex-1 px-4 py-2 gradient-primary text-white rounded-lg font-semibold hover:shadow-lg transition">Submit Request</button>
      </div>
     </form>
    </div>
   </div>
   </div>

   <script>

      function closeRequestModal() {
      document.getElementById('request-modal').classList.add('hidden');
      document.getElementById('request-type').value = '';
    }

    async function handleNewRequest(event) {
      event.preventDefault();
      if (!currentResident) return;

      const requestType = document.getElementById('request-type').value;
      const letterNumber = generateLetterNumber();

      if (allRequests.length >= 999) {
        showToast('Maximum limit of 999 requests reached', 'error');
        return;
      }

      const requestData = {
        resident_name: currentResident.name,
        email: currentResident.email,
        phone: currentResident.phone,
        request_type: requestType,
        status: 'Pending',
        created_at: new Date().toISOString(),
        letter_number: letterNumber,
        approved_by: '',
        approved_at: '',
        rejection_reason: '',
        notification_sent: 'true'
      };

      const result = await window.dataSdk.create(requestData);
      if (result.isOk) {
        closeRequestModal();
        showToast('Request submitted! Email notification sent.', 'success');
      } else {
        showToast('Error submitting request', 'error');
      }
    }

   </script>

</body>
</html>