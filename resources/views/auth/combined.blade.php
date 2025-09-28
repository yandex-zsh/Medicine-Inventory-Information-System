<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8">Welcome</h1>
        <div class="flex justify-center space-x-4">
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="openRegisterModal">
                Register
            </button>
            <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="openLoginModal">
                Login
            </button>
        </div>
    </div>

    @include('auth.register_modal')
    @include('auth.login_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openRegisterModalBtn = document.getElementById('openRegisterModal');
            const openLoginModalBtn = document.getElementById('openLoginModal');
            const registerModal = document.getElementById('registerModal');
            const loginModal = document.getElementById('loginModal');
            const closeRegisterModalBtn = registerModal ? registerModal.querySelector('#closeRegisterModal') : null;
            const closeLoginModalBtn = loginModal ? loginModal.querySelector('#closeLoginModal') : null;
            const openLoginFromRegisterLink = registerModal ? registerModal.querySelector('#openLoginFromRegister') : null;
            const openRegisterFromLoginLink = loginModal ? loginModal.querySelector('#openRegisterFromLogin') : null;

            function showModal(modalElement) {
                if (modalElement) modalElement.classList.remove('hidden');
            }

            function hideModal(modalElement) {
                if (modalElement) modalElement.classList.add('hidden');
            }

            if (openRegisterModalBtn) {
                openRegisterModalBtn.addEventListener('click', function() {
                    hideModal(loginModal); // Ensure other modal is closed
                    showModal(registerModal);
                });
            }

            if (openLoginModalBtn) {
                openLoginModalBtn.addEventListener('click', function() {
                    hideModal(registerModal); // Ensure other modal is closed
                    showModal(loginModal);
                });
            }

            if (closeRegisterModalBtn) {
                closeRegisterModalBtn.addEventListener('click', function() {
                    hideModal(registerModal);
                });
            }

            if (closeLoginModalBtn) {
                closeLoginModalBtn.addEventListener('click', function() {
                    hideModal(loginModal);
                });
            }

            if (openLoginFromRegisterLink) {
                openLoginFromRegisterLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    hideModal(registerModal);
                    showModal(loginModal);
                });
            }

            if (openRegisterFromLoginLink) {
                openRegisterFromLoginLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    hideModal(loginModal);
                    showModal(registerModal);
                });
            }

            // Handle errors after submission for initial display
            @if ($errors->any())
                const errors = {
                    @foreach ($errors->keys() as $key)
                        '{{ $key }}': true,
                    @endforeach
                };

                // Assuming 'name' error implies register form, 'email' or 'password' implies login
                if (errors['name'] && registerModal) {
                    showModal(registerModal);
                } else if ((errors['email'] || errors['password']) && loginModal) {
                    showModal(loginModal);
                }
            @endif

            // Activate specific modal if redirected with ?modal=login or ?modal=register
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('modal') === 'login' && loginModal) {
                showModal(loginModal);
            } else if (urlParams.get('modal') === 'register' && registerModal) {
                showModal(registerModal);
            }
        });
    </script>
</body>
</html>
