<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Commerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Create Account</h1>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Name Input -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">
                        Full Name
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror"
                        placeholder="John Doe"
                        required
                    >
                    @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">
                        Email Address
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('email') border-red-500 @enderror"
                        placeholder="you@example.com"
                        required
                    >
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('password') border-red-500 @enderror"
                        placeholder="Enter a strong password"
                        required
                    >
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">
                        Password must be at least 8 characters, include uppercase, lowercase, number, and special character.
                    </p>
                </div>

                <!-- Password Confirmation Input -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">
                        Confirm Password
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('password_confirmation') border-red-500 @enderror"
                        placeholder="Re-enter your password"
                        required
                    >
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Terms & Conditions Checkbox -->
                <div class="mb-6 flex items-start">
                    <input
                        type="checkbox"
                        id="agree"
                        name="agree"
                        class="w-4 h-4 text-green-600 rounded focus:ring-green-500 mt-1"
                        required
                    >
                    <label for="agree" class="ml-2 text-gray-700 text-sm">
                        I agree to the <a href="#" class="text-green-600 hover:underline">Terms & Conditions</a> and <a href="#" class="text-green-600 hover:underline">Privacy Policy</a>
                    </label>
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                >
                    Create Account
                </button>
            </form>

            <!-- Divider -->
            <div class="my-6 flex items-center">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="px-3 text-gray-500 text-sm">Or</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>

            <!-- Login Link -->
            <p class="text-center text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-green-600 hover:underline font-medium">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</body>
</html>
