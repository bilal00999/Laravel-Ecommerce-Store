<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Commerce Store</title>
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
                    <p class="text-indigo-100">Welcome to your store</p>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-b-2xl shadow-2xl p-8">
                <!-- Title -->
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Sign In</h2>
                <p class="text-gray-600 mb-8">Access your account to continue shopping</p>

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

                <!-- Login Form -->
                <form action="{{ route('login.web') }}" method="POST" class="space-y-6">
                    @csrf

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
                            placeholder="Enter your password"
                            required
                        >
                        @error('password')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer"
                        >
                        <label for="remember" class="ml-3 text-gray-700 cursor-pointer">
                            Remember me for 30 days
                        </label>
                    </div>

                    <!-- Sign In Button -->
                    <button
                        type="submit"
                        class="w-full gradient-bg hover:shadow-lg text-white font-bold py-3 px-4 rounded-lg transition duration-200 transform hover:scale-105"
                    >
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-8 flex items-center">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="px-4 text-gray-500 text-sm font-medium">New to E-Commerce?</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Register Link -->
                <a href="{{ route('register') }}" class="w-full block text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold py-3 px-4 rounded-lg transition duration-200 border-2 border-indigo-100">
                    Create Account
                </a>

                <p class="text-center text-gray-600 text-sm mt-6">
                    Forgot your password? <a href="#" class="text-indigo-600 hover:text-indigo-700 font-semibold">Reset it</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
