<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center p-8 bg-white shadow-lg rounded-lg max-w-md mx-auto">
        <i class="fas fa-tools text-5xl text-teal-500 mb-4"></i>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Server Sedang dalam Pemeliharaan</h1>
        <p class="text-gray-600">
            Kami sedang melakukan beberapa pembaruan untuk meningkatkan layanan kami. Mohon coba lagi nanti. Terima kasih atas kesabaran Anda.
        </p>
        <div class="mt-6">
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="text-sm text-gray-500 hover:text-gray-700">
                Kembali
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</body>
</html>
