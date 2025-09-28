<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Details - Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-purple-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-200">Dashboard</a>
                    <a href="{{ route('admin.bug-reports') }}" class="hover:text-purple-200">Bug Reports</a>
                    <a href="{{ route('admin.feature-suggestions') }}" class="hover:text-purple-200">Feature Suggestions</a>
                    <a href="{{ route('admin.activity-logs') }}" class="hover:text-purple-200">Activity Logs</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-purple-200">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Pharmacist Details: {{ $user->name }}</h2>

        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 font-semibold">Name:</p>
                    <p class="text-gray-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Email:</p>
                    <p class="text-gray-900">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Phone:</p>
                    <p class="text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">License Number:</p>
                    <p class="text-gray-900">{{ $user->license_number ?? 'N/A' }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-600 font-semibold">Address:</p>
                    <p class="text-gray-900">{{ $user->address ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Registration Date:</p>
                    <p class="text-gray-900">{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 font-semibold">Approval Status:</p>
                    <p class="text-gray-900">
                        @if($user->is_approved)
                            <span class="text-green-600 font-bold">Approved</span>
                        @else
                            <span class="text-yellow-600 font-bold">Pending</span>
                        @endif
                    </p>
                </div>
                @if($user->approved_by)
                <div>
                    <p class="text-gray-600 font-semibold">Approved By:</p>
                    <p class="text-gray-900">{{ $user->approvedBy->name }}</p>
                </div>
                @endif
                @if($user->approved_at)
                <div>
                    <p class="text-gray-600 font-semibold">Approved At:</p>
                    <p class="text-gray-900">{{ $user->approved_at->format('M d, Y H:i') }}</p>
                </div>
                @endif
                @if($user->rejection_reason)
                <div class="md:col-span-2">
                    <p class="text-gray-600 font-semibold">Rejection Reason:</p>
                    <p class="text-red-600">{{ $user->rejection_reason }}</p>
                </div>
                @endif
                @if($user->id_document)
                <div class="md:col-span-2">
                    <p class="text-gray-600 font-semibold">ID Document:</p>
                    <a href="{{ Storage::url($user->id_document) }}" target="_blank" class="text-blue-600 hover:underline">View ID Document</a>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Actions</h3>
            <div class="flex space-x-4">
                @if(!$user->is_approved)
                <form action="{{ route('admin.pharmacists.approve', $user) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Approve Pharmacist</button>
                </form>
                <button onclick="openRejectModal({{ $user->id }}, '{{ $user->name }}')" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Reject Pharmacist</button>
                @endif
                <form action="{{ route('admin.users.delete', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Delete User</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Pharmacist Modal (Duplicated for simplicity, ideally a shared component) -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Pharmacist: <span id="rejectPharmacistName"></span></h3>
                        <div class="mt-2">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Reason for Rejection</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" required></textarea>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Reject
                        </button>
                        <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(pharmacistId, pharmacistName) {
            document.getElementById('rejectPharmacistName').innerText = pharmacistName;
            document.getElementById('rejectForm').action = `/admin/pharmacists/${pharmacistId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejection_reason').value = ''; // Clear reason
        }
    </script>
</body>
</html>
