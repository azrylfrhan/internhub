<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Role Selection -->
    <div class="mb-6">
            <x-input-label for="password" :value="__('Password')" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />
                <button type="button" onclick="togglePassword('password', this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="eye-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Anak Magang</h3>
                    <p class="text-sm text-gray-600">Untuk peserta program InternHub</p>
                </div>
            </div>

            <div class="role-option border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-green-500 transition-colors" data-role="mentor">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Mentor/Admin</h3>
                    <p class="text-sm text-gray-600">Untuk pembimbing dan administrator</p>
                </div>
            </div>
        </div>
    </div>

    <form id="loginForm" method="POST" action="{{ route('login') }}" class="hidden">
        @csrf

        <!-- Selected Role (Hidden) -->
        <input type="hidden" name="role" id="selectedRole" value="magang">

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <button type="button" onclick="goBack()" class="text-sm text-gray-600 hover:text-gray-900">
                ← Kembali
            </button>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
        <div class="mt-6">
            <button id="loginBtn" type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <span id="loginBtnText">Login</span>
                <svg id="loginSpinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </button>
        </div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('svg path');
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('d', 'M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9-4-9-7s4-7 9-7 9 4 9 7c0 1.306-.835 2.417-2.125 3.825M15 12a3 3 0 11-6 0 3 3 0 016 0zm-2.25 6.825L21 21M3 3l18 18');
    } else {
        input.type = 'password';
        icon.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z');
    }
}

// Loading animation on login
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    if (form) {
        form.addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const text = document.getElementById('loginBtnText');
            const spinner = document.getElementById('loginSpinner');
            btn.disabled = true;
            text.textContent = 'Loading...';
            spinner.classList.remove('hidden');
        });
    }
});
</script>
        <div class="flex items-center justify-between mt-6">
            <button type="button" onclick="goBack()" class="text-sm text-gray-600 hover:text-gray-900">
                ← Kembali
            </button>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        // Role selection functionality
        const roleOptions = document.querySelectorAll('.role-option');
        const loginForm = document.getElementById('loginForm');
        const selectedRoleInput = document.getElementById('selectedRole');

        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                roleOptions.forEach(opt => {
                    opt.classList.remove('border-blue-500', 'border-green-500');
                    opt.classList.add('border-gray-200');
                });

                // Add active class to selected option
                const role = this.dataset.role;
                if (role === 'magang') {
                    this.classList.remove('border-gray-200');
                    this.classList.add('border-blue-500');
                } else {
                    this.classList.remove('border-gray-200');
                    this.classList.add('border-green-500');
                }

                // Set selected role and show form
                selectedRoleInput.value = role;
                setTimeout(() => {
                    document.querySelector('.role-option').parentElement.parentElement.style.display = 'none';
                    loginForm.classList.remove('hidden');
                    loginForm.classList.add('block');
                }, 300);
            });
        });

        function goBack() {
            loginForm.classList.remove('block');
            loginForm.classList.add('hidden');
            document.querySelector('.role-option').parentElement.parentElement.style.display = 'block';

            // Reset selection
            roleOptions.forEach(opt => {
                opt.classList.remove('border-blue-500', 'border-green-500');
                opt.classList.add('border-gray-200');
            });
        }

        // Auto-select magang role on page load
        document.addEventListener('DOMContentLoaded', function() {
            roleOptions[0].click();
        });
    </script>
</x-guest-layout>
