<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Welcome to your Dashboard!</h1>
        <p class="text-center text-gray-600">You are logged in.</p>
        
        <!-- Dashboard Options -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">General Dashboard</h2>
                <p class="text-gray-600 mb-4">Access your general account dashboard</p>
                <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Go to Dashboard
                </a>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Pharmacist Dashboard</h2>
                <p class="text-gray-600 mb-4">Manage medicine stock, view reports, and handle inventory</p>
                <a href="{{ route('pharmacist.dashboard') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Go to Pharmacist Dashboard
                </a>
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>
