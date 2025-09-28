<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Reports - Pharmacist Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold">Inventory Reports</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('pharmacist.dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                    <a href="{{ route('pharmacist.reports') }}" class="hover:text-blue-200">Reports</a>
                    <a href="{{ route('pharmacist.profile') }}" class="hover:text-blue-200">Profile</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-blue-200">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Report Period Selection -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Generate Report</h2>
            <form method="GET" action="{{ route('pharmacist.reports') }}" class="flex space-x-4">
                <select name="period" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily Report</option>
                    <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly Report</option>
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly Report</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Generate Report
                </button>
            </form>
        </div>

        <!-- Report Summary -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ ucfirst($period) }} Inventory Report</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-800">Total Medicines</h3>
                    <p class="text-2xl font-bold text-blue-900">{{ $medicines->count() }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-green-800">Total Value</h3>
                    <p class="text-2xl font-bold text-green-900">${{ number_format($totalValue, 2) }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-yellow-800">Low Stock Items</h3>
                    <p class="text-2xl font-bold text-yellow-900">{{ $medicines->where('stock_status', 'low_stock')->count() }}</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-red-800">Expired Items</h3>
                    <p class="text-2xl font-bold text-red-900">{{ $medicines->where('stock_status', 'expired')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Detailed Report Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Detailed Inventory</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Manufacturer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($medicines as $medicine)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}</div>
                                @if($medicine->generic_name)
                                    <div class="text-sm text-gray-500">{{ $medicine->generic_name }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medicine->manufacturer }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medicine->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($medicine->unit_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($medicine->quantity * $medicine->unit_price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $medicine->expiry_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($medicine->stock_status === 'expired')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                                @elseif($medicine->stock_status === 'expiring_soon')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Expiring Soon</span>
                                @elseif($medicine->stock_status === 'low_stock')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($medicines->count() === 0)
                <div class="text-center py-8">
                    <p class="text-gray-500">No medicines found for the selected period.</p>
                </div>
            @endif
        </div>

        <!-- Print Button -->
        <div class="mt-6 text-center">
            <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Print Report
            </button>
        </div>
    </div>

    <style>
        @media print {
            nav, button {
                display: none !important;
            }
            body {
                background: white !important;
            }
        }
    </style>
</body>
</html>
