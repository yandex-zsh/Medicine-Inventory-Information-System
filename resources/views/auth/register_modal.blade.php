<div id="registerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-2xl font-bold text-gray-900">Register</h3>
            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl" id="closeRegisterModal">&times;</button>
        </div>
        <div class="mt-2">
            @include('auth.register')
            <p class="text-center text-gray-700 text-sm mt-4">
                Already have an account?
                <a href="#" class="font-bold text-blue-600 hover:text-blue-800" id="openLoginFromRegister">Login</a>
            </p>
        </div>
    </div>
</div>
