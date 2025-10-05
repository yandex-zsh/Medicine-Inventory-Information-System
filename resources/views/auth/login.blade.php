<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <form method="POST" action="{{ route('login') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h2>

            @if (session('status'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <div class="relative">
                    <input type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" id="password" name="password" required>
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5" onclick="togglePasswordVisibility('password')">
                        <svg class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path id="password-eye-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path id="password-slash-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" class="mr-2 leading-tight" id="remember" name="remember">
                <label class="text-sm text-gray-700" for="remember">Remember me</label>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Login
                </button>
                <a href="{{ route('register') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                    Register as Pharmacist
                </a>
            </div>
        </form>
    </div>
    <script>
        function togglePasswordVisibility(id) {
            const passwordInput = document.getElementById(id);
            const eyeIcon = document.getElementById(id + '-eye-icon');
            const slashIcon = document.getElementById(id + '-slash-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
                slashIcon.setAttribute('d', 'M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('d', 'M13.879 16.121a3 3 0 11-4.242-4.242M15 12a3 3 0 11-6 0m6 0a3 3 0 10-6 0M9.88 12.12a3 3 0 11-4.242-4.242M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7zM2.458 12l.716-.716M21.542 12l-.716-.716m-18.784 0l.716.716m18.784-.716L21.542 12M12 21.542l-.716-.716m0-18.784l.716-.716m-18.784 0l.716-.716');
                slashIcon.setAttribute('d', 'M10.05 15.05L12 12m0 0l1.95-1.95M12 12l-1.95 1.95M12 12l1.95 1.95M5.5 5.5l1.45 1.45M18.5 18.5l-1.45-1.45M19.95 4.05L18.5 5.5m-1.45-1.45l-1.45 1.45M4.05 19.95L5.5 18.5m1.45 1.45l-1.45-1.45');
            }
        }
    </script>
</body>
</html>
