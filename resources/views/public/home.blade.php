<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Pharmacy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold">Our Pharmacy</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('public.home') }}" class="hover:text-blue-200">Home</a>
                    <a href="{{ route('public.medicines') }}" class="hover:text-blue-200">Browse Medicines</a>
                    <a href="{{ route('login') }}" class="hover:text-blue-200">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-blue-200">Register as Pharmacist</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-10">Discover Our Medicines</h2>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('public.medicines') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" placeholder="Search by name or symptom..." class="flex-grow border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <select name="category" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    <!-- Categories will be dynamically loaded in browseMedicines view -->
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </form>
        </div>

        <!-- Trending Medicines -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Trending Medicines (by Views)</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($trendingMedicines as $medicine)
                    <div class="border border-gray-200 rounded-lg p-4 flex flex-col justify-between items-center text-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $medicine->name }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $medicine->manufacturer }}</p>
                        <p class="text-xl font-bold text-blue-600 mb-4">${{ number_format($medicine->unit_price, 2) }}</p>
                        <a href="{{ route('public.medicines.show', $medicine) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded">View Details</a>
                    </div>
                @empty
                    <p class="col-span-full text-gray-600 text-center">No trending medicines available at the moment.</p>
                @endforelse
            </div>
        </div>

        <!-- High Sales Medicines -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">High Sales Medicines</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($highSalesMedicines as $medicine)
                    <div class="border border-gray-200 rounded-lg p-4 flex flex-col justify-between items-center text-center">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $medicine->name }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $medicine->manufacturer }}</p>
                        <p class="text-xl font-bold text-blue-600 mb-4">${{ number_format($medicine->unit_price, 2) }}</p>
                        <a href="{{ route('public.medicines.show', $medicine) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded">View Details</a>
                    </div>
                @empty
                    <p class="col-span-full text-gray-600 text-center">No high sales medicines available at the moment.</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Links / Call to Action -->
        <div class="mt-8 text-center">
            <a href="{{ route('public.medicines') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg">
                View All Medicines
            </a>
        </div>
    </div>
</body>
</html>
