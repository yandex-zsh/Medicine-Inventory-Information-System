<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile & Account - Pharmacist Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-2xl font-bold">Profile & Account</h1>
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

    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Profile Information</h2>
            
            <form action="{{ route('pharmacist.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ Auth::user()->email }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" name="phone" value="{{ Auth::user()->phone ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">License Number</label>
                        <input type="text" name="license_number" value="{{ Auth::user()->license_number ?? '' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ Auth::user()->address ?? '' }}</textarea>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Change Password</h2>
            
            <form action="{{ route('pharmacist.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Change Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Settings -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Account Settings</h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Email Notifications</h3>
                        <p class="text-sm text-gray-500">Receive notifications about low stock and expiring medicines</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Auto Backup</h3>
                        <p class="text-sm text-gray-500">Automatically backup inventory data daily</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Two-Factor Authentication</h3>
                        <p class="text-sm text-gray-500">Add an extra layer of security to your account</p>
                    </div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                        Enable
                    </button>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mt-8">
            <h2 class="text-xl font-bold text-red-800 mb-4">Danger Zone</h2>
            <p class="text-red-700 mb-4">These actions are irreversible. Please be careful.</p>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 border border-red-200 rounded-lg bg-white">
                    <div>
                        <h3 class="text-lg font-medium text-red-900">Delete Account</h3>
                        <p class="text-sm text-red-700">Permanently delete your account and all associated data</p>
                    </div>
                    <button onclick="confirmDeleteAccount()" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteAccount() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                // This would typically redirect to a delete account route
                alert('Account deletion functionality would be implemented here.');
            }
        }
    </script>
</body>
</html>
