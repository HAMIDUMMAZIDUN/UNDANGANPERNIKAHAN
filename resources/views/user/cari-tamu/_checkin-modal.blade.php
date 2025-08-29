<div id="checkInModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="bg-amber-800 text-white p-4 rounded-t-lg flex justify-between items-center">
            <h3 class="text-lg font-semibold">Check-in Tamu Terdaftar</h3>
            <button id="closeModalBtn" class="text-white hover:text-amber-200 text-2xl leading-none">&times;</button>
        </div>
        <div class="p-6">
            <form id="checkInForm" method="POST" action="">
                @csrf
                
                {{-- Data Tamu --}}
                <p id="guestName" class="text-xl font-bold text-slate-800"></p>
                {{-- ELEMEN BARU UNTUK KATEGORI --}}
                <p id="guestAffiliation" class="text-md text-slate-600 mb-4 -mt-1"></p>
                
                <label for="jumlah_tamu" class="block text-sm font-medium text-slate-700">Jumlah tamu yang hadir</label>
                <div class="mt-1">
                    <input type="number" name="jumlah_tamu" id="jumlah_tamu" value="1" min="1" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button id="cancelModalBtn" type="button" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 border border-transparent rounded-md hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-amber-600 px-4 py-2 text-sm font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Check-in
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
