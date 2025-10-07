<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $design->name }} - Preview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap');
        body {
            padding-top: 60px; /* Prevent content from hiding behind fixed navbar */
        }
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Poppins', sans-serif; }
        #preview-frame {
            transition: max-width 0.3s ease-in-out;
            margin-left: auto;
            margin-right: auto;
        }
        .device-btn.active {
            background-color: #f1f5f9; /* bg-slate-100 */
            color: #f97316; /* text-orange-500 */
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
        <div class="max-w-7xl mx-auto px-4 py-2 flex justify-between items-center">
            <h1 class="font-medium text-gray-700">Preview Mode</h1>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 border-r pr-4 mr-2">
                    <button id="btn-mobile" onclick="toggleDevicePreview('mobile')" class="device-btn p-2 hover:bg-gray-100 rounded-lg" title="Mobile Preview">
                        <i class="fas fa-mobile-alt"></i>
                    </button>
                    <button id="btn-tablet" onclick="toggleDevicePreview('tablet')" class="device-btn p-2 hover:bg-gray-100 rounded-lg" title="Tablet Preview">
                        <i class="fas fa-tablet-alt"></i>
                    </button>
                    <button id="btn-desktop" onclick="toggleDevicePreview('desktop')" class="device-btn p-2 hover:bg-gray-100 rounded-lg active" title="Desktop Preview">
                        <i class="fas fa-desktop"></i>
                    </button>
                </div>
                {{-- FIX: Changed route name from admin.design.edit to admin.design.editor --}}
                <a href="{{ route('admin.design.editor', $design->id) }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition-colors">
                    Back to Editor
                </a>
            </div>
        </div>
    </div>

    <div id="preview-container" class="pt-8 transition-all duration-300">
        <div id="preview-frame" class="bg-white shadow-lg">
            {!! $renderedContent !!}
        </div>
    </div>

    <script>
    // Set desktop as default on load
    window.onload = () => toggleDevicePreview('desktop');

    function toggleDevicePreview(device) {
        const container = document.getElementById('preview-frame');
        const buttons = document.querySelectorAll('.device-btn');
        
        // Remove active class from all buttons
        buttons.forEach(btn => btn.classList.remove('active'));

        switch(device) {
            case 'mobile':
                container.style.maxWidth = '375px';
                document.getElementById('btn-mobile').classList.add('active');
                break;
            case 'tablet':
                container.style.maxWidth = '768px';
                document.getElementById('btn-tablet').classList.add('active');
                break;
            case 'desktop':
                container.style.maxWidth = '1200px';
                document.getElementById('btn-desktop').classList.add('active');
                break;
        }
    }
    </script>
</body>
</html>
