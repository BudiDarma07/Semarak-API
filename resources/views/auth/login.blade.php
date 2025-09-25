<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    {{-- Setting Font Poppins --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <link rel="icon" href="{{ asset('images/logo-prov-bali.png') }}" type="image/png">
</head>
<body class="bg-white h-screen overflow-hidden">

    <div class="flex h-screen">
        <div class="hidden md:block md:w-1/2 lg:w-3/5">
            <img src="{{asset('images/Turyapada-tower-2261660011.webp')}}" alt="Turyapada Tower" class="object-cover w-full h-full rounded-r-2xl">
        </div>

        <div class="w-full md:w-1/2 lg:w-2/5 flex items-center justify-center p-8">
            <div class="w-full max-w-lg">

                <div class="flex items-center justify-center mb-6">
                    <img src="{{asset('images/logo-turyapada.webp')}}" alt="Logo Turyapada" class="h-12 mr-4">
                </div>

                <p class="text-center text-sm text-gray-600 mb-8">
                    Selamat datang di Sistem Elektronik Monitoring Aset dan Rencana Aktivitas Kontinu UPTD. Turyapada Tower Bali
                </p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-base font-semibold text-gray-800 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 @error('email') @enderror" required autofocus>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-base font-semibold text-gray-800 mb-1">Password</label>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-300">Login</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>