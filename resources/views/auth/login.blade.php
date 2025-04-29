<x-layouts.auth-layout pageTitle="Login">
    <div class="flex min-h-screen bg-white">
        <div class="w-1/2 bg-cover bg-center" style="background-image: url('/caminho/para/sua/imagem.jpg');"></div>

        <div class="bg-white lg:w-1/2 lg:mx-auto sm:p-6 p-4 rounded-2xl shadow-xl flex flex-col justify-center">
            <h2 class="text-center text-2xl/9 font-bold tracking-tight text-black">Login to your account</h2>

            <form class="space-y-4 mt-4" action="{{ route('authenticate') }}" method="POST" novalidate>
                @csrf
                <div>
                    <label for="email" class="block text-sm/6 font-medium text-black">Email</label>
                    <div class="mt-2">
                        <input type="email" name="email" id="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               class="block w-full text-black rounded-md bg-gray-100 p-3 text-base placeholder:text-gray-500 focus:outline-2 focus:outline-offset-2 focus:outline-yellow-300 sm:text-sm/6">
                    </div>
                    @error('email')
                    <p class="text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm/6 font-medium text-black">Password</label>
                    </div>
                    <div class="mt-2">
                        <input type="password" name="password" id="password" autocomplete="current-password" required
                               value="{{ old('password') }}"
                               class="block w-full text-black rounded-md bg-gray-100 p-3 text-base placeholder:text-gray-500 focus:outline-2 focus:outline-offset-2 focus:outline-yellow-300 sm:text-sm/6">
                    </div>
                    @error('password')
                    <p class="text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="text-sm text-end">
                    <a href="{{ route('forgotPassword') }}"
                       class="font-semibold text-yellow-300 hover:text-yellow-300/90">Forgot password?</a>
                </div>

                <div>
                    <button type="submit"
                            class="flex cursor-pointer w-full justify-center rounded-md bg-yellow-100 p-3 text-sm/6 font-semibold text-black shadow-xs hover:bg-yellow-100/90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-yellow-300">
                        Sign in with email
                    </button>
                </div>
            </form>

            @if(session('invalid_login'))
                <div class="flex justify-center items-center bg-red-400 text-white rounded-md py-3 mt-4">
                    <p>{{ session('invalid_login') }}</p>
                </div>
            @endif

            @if(session('password_reset'))
                <div class="flex justify-center items-center bg-green-400 text-white rounded-md py-3 mt-4">
                    <p>{{ session('password_reset') }}</p>
                </div>
            @endif

            @if(session('account_deleted'))
                <div class="flex justify-center items-center bg-green-400 text-white rounded-md py-3 mt-4">
                    <p>{{ session('account_deleted') }}</p>
                </div>
            @endif


            <p class="mt-10 text-center text-sm/6 text-gray-500">
                Don’t have an account?
                <a href="{{ route('register') }}"
                   class="font-semibold text-yellow-300 hover:text-yellow-300/90">
                    Get Started
                </a>
            </p>
        </div>
    </div>
</x-layouts.auth-layout>
