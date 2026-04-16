<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Commerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <div class="w-full max-w-md">
            <!-- Header Card with Logo -->
            <div class="text-center mb-8">
                <div class="gradient-bg text-white rounded-t-2xl p-8 shadow-lg">
                    <h1 class="text-4xl font-bold mb-2">E-Commerce</h1>
                    <p class="text-indigo-100">Join our community</p>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-b-2xl shadow-2xl p-8">
                <!-- Title -->
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Create Account</h2>
                <p class="text-gray-600 mb-8">Sign up to start shopping with us</p>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg">
                        <p class="font-semibold mb-2">{{ $errors->count() }} error(s) found:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Registration Form -->
                <form action="{{ route('register') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-gray-700 font-semibold mb-3">
                            Full Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                            placeholder="John Doe"
                            required
                        >
                        @error('name')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-gray-700 font-semibold mb-3">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                            placeholder="you@example.com"
                            required
                        >
                        @error('email')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-gray-700 font-semibold mb-3">
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                            placeholder="Enter a strong password"
                            required
                        >
                        @error('password')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                        <p class="text-gray-600 text-xs mt-2 bg-gray-50 p-3 rounded">
                            <strong>Password requirements:</strong> 8+ characters, uppercase, lowercase, number, special character
                        </p>
                    </div>

                    <!-- Password Confirmation Input -->
                    <div>
                        <label for="password_confirmation" class="block text-gray-700 font-semibold mb-3">
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Re-enter your password"
                            required
                        >
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                    </div>

                    <!-- Terms & Conditions Checkbox -->
                    <div class="flex items-start">
                        <input
                            type="checkbox"
                            id="agree"
                            name="agree"
                            class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer mt-1"
                            required
                        >
                        <label for="agree" class="ml-3 text-gray-700 text-sm cursor-pointer">
                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-700 font-semibold">Terms & Conditions</a> and <a href="#" class="text-indigo-600 hover:text-indigo-700 font-semibold">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Register Button -->
                    <button
                        type="submit"
                        class="w-full gradient-bg hover:shadow-lg text-white font-bold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105"
                    >
                        Create Account
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-8 flex items-center">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="px-4 text-gray-500 text-sm font-medium">Already have an account?</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Login Link -->
                <a href="{{ route('login') }}" class="w-full block text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold py-3 px-4 rounded-lg transition duration-200 border-2 border-indigo-100">
                    Sign In
                </a>

                <!-- Additional Links -->
                <p class="text-center text-gray-600 text-sm mt-6">
                    By signing up, you agree to our service terms. <a href="#" class="text-indigo-600 hover:text-indigo-700 font-semibold">Learn more</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
