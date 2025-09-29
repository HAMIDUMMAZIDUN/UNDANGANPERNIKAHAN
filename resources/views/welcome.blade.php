<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Undangan Digital</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                        'serif': ['"Playfair Display"', 'serif'],
                    },
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    
</head>
<body class="font-sans text-stone-800 antialiased min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/bg-pernikahan.png') }}');">

    <div class="min-h-screen flex items-center justify-center p-6">
        
        <div class="text-center max-w-2xl w-full">
            <h1 class="font-serif text-4xl md:text-6xl font-extrabold text-white tracking-tight" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.4);">
                Wujudkan Undangan Impian Anda
            </h1>
            
            <p class="mt-4 text-base md:text-lg text-white max-w-xl mx-auto" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.4);">
                Platform undangan digital modern untuk hari spesial Anda. Desain elegan, mudah dibagikan, dan ramah lingkungan.
            </p>
            
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('katalog.index') }}" 
                   class="w-full sm:w-auto bg-amber-500 text-white font-semibold px-8 py-3 rounded-full shadow-lg hover:bg-amber-600 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-opacity-50">
                    Lihat Katalog
                </a>
                <a href="{{ route('login') }}" 
                   class="w-full sm:w-auto border-2 border-white text-white font-semibold px-8 py-3 rounded-full bg-black/20 backdrop-blur-sm hover:bg-white hover:text-amber-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-white">
                    Masuk
                </a>
            </div>
            
        </div>
    </div>

</body>
</html>
