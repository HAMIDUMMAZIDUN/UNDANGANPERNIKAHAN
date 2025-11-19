<x-app-layout>
    <div class="flex flex-col items-center justify-center h-screen pb-20" x-data="scannerApp()">
        <h2 class="text-2xl font-bold mb-4">Scan Barcode Tamu</h2>
        
        <input type="text" x-model="code" @keydown.enter="submitScan()" 
               class="w-full p-4 text-center text-xl border-2 border-blue-500 rounded-lg bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-300" 
               placeholder="Scan Barcode di sini..." autofocus>

        <div x-show="message" class="mt-4 p-4 rounded-lg text-center" :class="status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
            <span x-text="message" class="text-lg font-bold"></span>
        </div>
    </div>

    <script>
        function scannerApp() {
            return {
                code: '',
                message: '',
                status: '',
                submitScan() {
                    if(this.code.length < 1) return;

                    fetch('/api/scan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ barcode_code: this.code })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.message = data.message;
                        this.status = data.status;
                        this.code = ''; // Reset input untuk scan berikutnya
                    });
                }
            }
        }
    </script>
</x-app-layout>