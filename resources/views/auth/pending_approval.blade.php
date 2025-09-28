<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approval</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="container mx-auto px-4 py-8 text-center">
        <h1 class="text-4xl font-bold text-yellow-600 mb-6">Account Pending Approval</h1>
        <p class="text-lg text-gray-700 mb-4">Thank you for registering as a pharmacist. Your account is currently under review by an administrator.</p>
        <p class="text-md text-gray-600 mb-8">You will receive an email notification once your account has been approved or if further information is required.</p>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Logout
            </button>
        </form>
    </div>
</body>
</html>
