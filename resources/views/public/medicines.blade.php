<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Medicines - Our Pharmacy</title>
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
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Browse All Medicines</h2>

        <!-- Search and Filter Form -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form action="{{ route('public.medicines') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" placeholder="Search by name or symptom..." value="{{ request('search') }}" class="flex-grow border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <select name="category" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Search
                </button>
            </form>
        </div>

        <!-- Medicine Listing -->
        @if($medicines->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($medicines as $medicine)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                        <div class="p-4 flex-grow">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $medicine->name }}</h3>
                            <p class="text-sm text-gray-600 mb-1"><strong>Generic:</strong> {{ $medicine->generic_name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 mb-1"><strong>Manufacturer:</strong> {{ $medicine->manufacturer }}</p>
                            @if ($medicine->image_path)
                                <img src="{{ asset('storage/' . $medicine->image_path) }}" alt="{{ $medicine->name }}" class="w-full h-32 object-cover mb-4 rounded-md">
                            @else
                                <div class="w-full h-32 flex items-center justify-center bg-gray-200 mb-4 rounded-md text-gray-500 text-xs">No Image</div>
                            @endif
                            <p class="text-lg font-bold text-blue-600 mt-2 mb-2">${{ number_format($medicine->unit_price, 2) }}</p>
                            <p class="text-xs text-gray-500">Expiry: {{ $medicine->expiry_date->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">Category: {{ $medicine->category ?? 'N/A' }}</p>
                            @if($medicine->symptoms_treated)
                                <p class="text-xs text-gray-500 mt-1">Treats: {{ Str::limit($medicine->symptoms_treated, 50) }}</p>
                            @endif
                        </div>
                        <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-center">
                            <a href="{{ route('public.medicines.show', $medicine) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-bold py-2 px-4 rounded">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $medicines->links() }}
            </div>
        @else
            <p class="text-gray-600 text-center">No medicines found matching your criteria.</p>
        @endif
    </div>
</body>
</html>
