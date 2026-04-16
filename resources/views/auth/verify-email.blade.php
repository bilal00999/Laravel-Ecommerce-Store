<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - E-Commerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Verify Your Email</h1>

            <!-- Success Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    ✅ A fresh verification link has been sent to your email address.
                </div>
            @endif

            <!-- Main Message -->
            <div class="mb-6 text-gray-600">
                <p class="mb-4">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? 
                </p>
                <p>
                    If you didn't receive the email, we will gladly send you another.
                </p>
            </div>

            <!-- Resend Verification Email Form -->
            <form action="{{ route('verification.send') }}" method="POST" class="mb-6">
                @csrf
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                >
                    Resend Verification Email
                </button>
            </form>

            <!-- Logout Form -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="w-full bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition duration-200"
                >
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</body>
</html>
