<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Bagian Kiri - Ilustrasi -->
        <div class="hidden lg:flex flex-1 bg-white items-center justify-center p-12">
            <div class="max-w-md">
                <img src="/images/ilustrasi.svg" 
                     alt="Smart Inventory Illustration" 
                     class="w-full h-auto">
            </div>
        </div>

        <!-- Bagian Kanan - Formulir -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="max-w-md w-full">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                        Adventure starts here 🚀
                    </h2>
                    <p class="text-sm text-gray-500">
                        Make your app management easy and fun!
                    </p>
                </div>

                <form class="space-y-5" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Name
                        </label>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            required 
                            class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm @error('name') border-red-500 @enderror"
                            placeholder="Enter your name"
                            value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm @error('email') border-red-500 @enderror"
                            placeholder="Enter your email"
                            value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password
                        </label>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm @error('password') border-red-500 @enderror"
                            placeholder="············">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Confirm Password
                        </label>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            required 
                            class="appearance-none block w-full px-3 py-2.5 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                            placeholder="············">
                    </div>

                    <div class="flex items-start">
                        <input 
                            id="terms" 
                            name="terms" 
                            type="checkbox" 
                            required
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-0.5">
                        <label for="terms" class="ml-2 block text-sm text-gray-700">
                            I agree to 
                            <a href="#" class="text-purple-600 hover:text-purple-700">privacy policy & terms</a>
                        </label>
                    </div>

                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded" role="alert">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                        Sign up
                    </button>

                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-700">
                                Sign in instead
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>