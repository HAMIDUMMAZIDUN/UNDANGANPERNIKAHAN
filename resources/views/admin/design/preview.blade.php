<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $design->name ?? 'Preview Desain' }} - Preview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap');
        .font-serif { font-family: 'Playfair Display', serif; }
        .font-sans { font-family: 'Poppins', sans-serif; }
        body { padding-top: 64px; }
        #preview-frame {
            transition: max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 0 auto;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            background: white;
        }
        .device-btn.active {
            background-color: #f97316;
            color: white;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                        'serif': ['Playfair Display', 'serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 font-sans" x-data="previewHandler()" x-init="init()">
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-md z-50 h-16 flex items-center">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <h1 class="font-semibold text-gray-800 text-lg">
                        <span class="text-orange-500">Preview:</span> 
                        {{ $design->name ?? 'Desain Baru' }}
                    </h1>
                    @if($design->updated_at)
                        <span class="text-sm text-gray-400 hidden md:block">
                            Diperbarui: {{ $design->updated_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2 border-r border-gray-200 pr-6">
                        <button @click="setDevice('mobile')" :class="{ 'active': currentDevice === 'mobile' }" class="device-btn p-2 rounded-lg hover:bg-orange-100" title="Mobile (Alt+1)">
                            <i class="fas fa-mobile-alt fa-fw text-lg" aria-hidden="true"></i>
                        </button>
                        <button @click="setDevice('tablet')" :class="{ 'active': currentDevice === 'tablet' }" class="device-btn p-2 rounded-lg hover:bg-orange-100" title="Tablet (Alt+2)">
                            <i class="fas fa-tablet-alt fa-fw text-lg" aria-hidden="true"></i>
                        </button>
                        <button @click="setDevice('desktop')" :class="{ 'active': currentDevice === 'desktop' }" class="device-btn p-2 rounded-lg hover:bg-orange-100" title="Desktop (Alt+3)">
                            <i class="fas fa-desktop fa-fw text-lg" aria-hidden="true"></i>
                        </button>
                    </div>

                    @if($design->id)
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.design.editor', $design->id) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button @click="sharePreview" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors w-[110px]">
                                <span x-show="!notification.show" x-transition><i class="fas fa-share-alt mr-1"></i> Bagikan</span>
                                <span x-show="notification.show" x-transition><i class="fas fa-check mr-1"></i> Disalin!</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <div id="preview-container">
            <div id="preview-frame" class="rounded-lg overflow-hidden" 
                 :style="{ 'max-width': deviceWidths[currentDevice] }">
                {!! $renderedContent !!}
            </div>
        </div>
    </main>

    <script>
    function previewHandler() {
        return {
            currentDevice: 'desktop',
            deviceWidths: {
                mobile: '375px',
                tablet: '768px',
                desktop: '1200px'
            },
            notification: {
                show: false,
                timer: null
            },

            init() {
                this.setDevice('desktop');
                
                document.addEventListener('keydown', (e) => {
                    if (e.altKey) {
                        e.preventDefault();
                        if (e.key === '1') this.setDevice('mobile');
                        if (e.key === '2') this.setDevice('tablet');
                        if (e.key === '3') this.setDevice('desktop');
                    }
                });
            },

            setDevice(device) {
                this.currentDevice = device;
                window.dispatchEvent(new Event('resize'));
            },

            async sharePreview() {
                if (this.notification.show) return;

                @if($design->id)
                    const shareUrl = '{{ route("katalog.design.demo", $design->id) }}';
                    try {
                        await navigator.clipboard.writeText(shareUrl);
                        this.showNotification();
                    } catch (err) {
                        console.error('Gagal menyalin link:', err);
                        alert('Gagal menyalin link.');
                    }
                @endif
            },

            showNotification() {
                if (this.notification.timer) clearTimeout(this.notification.timer);
                this.notification.show = true;
                this.notification.timer = setTimeout(() => {
                    this.notification.show = false;
                }, 2000);
            }
        }
    }
    </script>
</body>
</html>