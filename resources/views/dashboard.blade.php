<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Commerce Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">E-Commerce Store</h1>
            <div class="flex items-center gap-4">
                <span class="text-gray-700">Welcome, {{ auth()->user()->name }}!</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- User Info Card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Profile</h2>
                <p class="text-gray-600"><strong>Name:</strong> {{ auth()->user()->name }}</p>
                <p class="text-gray-600"><strong>Email:</strong> {{ auth()->user()->email }}</p>
                @if(auth()->user()->is_admin)
                    <p class="text-yellow-600 font-bold mt-2">🔐 Admin Account</p>
                @endif
            </div>

            <!-- Orders Card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Orders</h2>
                <p class="text-gray-600 mb-4">You have <strong>0 orders</strong></p>
                <a href="/orders" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    View Orders
                </a>
            </div>

            <!-- Quick Links Card -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Links</h2>
                <ul class="space-y-2">
                    <li><a href="/products" class="text-blue-600 hover:underline">Browse Products</a></li>
                    <li><a href="/wishlist" class="text-blue-600 hover:underline">My Wishlist</a></li>
                    <li><a href="/profile" class="text-blue-600 hover:underline">Edit Profile</a></li>
                </ul>
            </div>
        </div>

        @if(auth()->user()->is_admin)
            <!-- Admin Section -->
            <div class="mt-8 bg-yellow-50 border-2 border-yellow-200 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-yellow-800 mb-4">👨‍💼 Admin Panel</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="/admin/dashboard" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded text-center">
                        Dashboard
                    </a>
                    <a href="/admin/products" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded text-center">
                        Products
                    </a>
                    <a href="/admin/orders" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded text-center">
                        Orders
                    </a>
                    <a href="/admin/users" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded text-center">
                        Users
                    </a>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
