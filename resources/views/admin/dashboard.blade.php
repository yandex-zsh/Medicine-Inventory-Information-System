<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Welcome, Admin!</h2>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Total Users</h3>
                <p class="text-4xl font-bold text-purple-600">{{ $totalUsers }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pending Pharmacists</h3>
                <p class="text-4xl font-bold text-yellow-500">{{ $pendingPharmacists->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Approved Pharmacists</h3>
                <p class="text-4xl font-bold text-green-500">{{ $approvedPharmacists->count() }}</p>
            </div>
        </div>

        <!-- Pharmacist Management Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Pharmacist Management</h2>

            <!-- Pending Pharmacists -->
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Pending Approvals ({{ $pendingPharmacists->count() }})</h3>
            @if($pendingPharmacists->count() > 0)
            <div class="overflow-x-auto mb-8">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Document</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingPharmacists as $pharmacist)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pharmacist->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pharmacist->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pharmacist->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($pharmacist->id_document)
                                    <a href="{{ Storage::url($pharmacist->id_document) }}" target="_blank" class="text-blue-600 hover:underline">View ID</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-2">
                                <form action="{{ route('admin.pharmacists.approve', $pharmacist) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                </form>
                                <button onclick="openRejectModal({{ $pharmacist->id }}, '{{ $pharmacist->name }}')" class="text-red-600 hover:text-red-900">Reject</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-600 mb-8">No pending pharmacist approvals.</p>
            @endif

            <!-- Approved Pharmacists -->
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Approved Pharmacists ({{ $approvedPharmacists->count() }})</h3>
            @if($approvedPharmacists->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($approvedPharmacists as $pharmacist)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pharmacist->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pharmacist->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pharmacist->approvedBy ? $pharmacist->approvedBy->name : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pharmacist->approved_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-2">
                                <a href="{{ route('admin.pharmacists.show', $pharmacist) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                <form action="{{ route('admin.users.delete', $pharmacist) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-600">No approved pharmacists.</p>
            @endif
        </div>
    </div>

    <!-- Reject Pharmacist Modal -->
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
