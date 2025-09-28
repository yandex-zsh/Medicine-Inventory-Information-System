<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - Admin Dashboard</title>
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
        <h2 class="text-3xl font-bold text-gray-800 mb-8">User Activity Logs</h2>

        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-gray-600">This section would display detailed user activity logs (e.g., login times, actions performed).</p>
            <p class="text-gray-600 mt-2">Implementation of a robust logging system is required for this feature.</p>
            <!-- Example Placeholder for Logs -->
            <div class="mt-6 space-y-4">
                <div class="border-b border-gray-200 pb-4">
                    <p class="text-sm text-gray-800 font-semibold">[Timestamp] - [User Name] ([Role]) - [Action Performed]</p>
                    <p class="text-xs text-gray-500">e.g., 2025-09-28 10:30:15 - John Doe (Pharmacist) - Added medicine "Aspirin"</p>
                </div>
                <div class="border-b border-gray-200 pb-4">
                    <p class="text-sm text-gray-800 font-semibold">[Timestamp] - [User Name] ([Role]) - [Action Performed]</p>
                    <p class="text-xs text-gray-500">e.g., 2025-09-28 10:45:00 - Admin (Admin) - Approved Pharmacist "Jane Smith"</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
