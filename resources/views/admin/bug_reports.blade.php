<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bug Reports - Admin Dashboard</title>
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
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Bug Reports</h2>

        <div class="bg-white rounded-lg shadow-md p-6">
            @if($bugReports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted By</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bugReports as $report)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $report->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->user ? $report->user->name : 'Guest' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->title }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($report->description, 50) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($report->status === 'open') bg-red-100 text-red-800
                                            @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-800
                                            @elseif($report->status === 'resolved') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $report->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="alert('View bug report details for ID: {{ $report->id }}')" class="text-indigo-600 hover:text-indigo-900">View</button>
                                        <!-- Add update status forms/modals here -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600">No bug reports submitted.</p>
            @endif
        </div>
    </div>
</body>
</html>
