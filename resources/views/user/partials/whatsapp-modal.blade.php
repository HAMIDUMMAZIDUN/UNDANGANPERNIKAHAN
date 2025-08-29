<div id="whatsappModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-2xl bg-stone-100 font-sans">
        
        {{-- Header Modal --}}
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-xl font-bold text-stone-800">WhatsApp Message</h3>
            <button onclick="closeWhatsAppModal()" class="text-stone-500 hover:text-stone-800 text-3xl font-light">&times;</button>
        </div>

        {{-- Konten Modal --}}
        <div class="mt-2">
            {{-- Tombol Template --}}
            <div class="flex flex-wrap gap-2 mb-4">
                <button onclick="setTemplate('formal')" class="px-4 py-1.5 text-sm bg-stone-700 text-white rounded-full hover:bg-stone-800 transition">Formal</button>
                <button onclick="setTemplate('islami')" class="px-4 py-1.5 text-sm bg-stone-700 text-white rounded-full hover:bg-stone-800 transition">Islami</button>
                <button onclick="setTemplate('kristen')" class="px-4 py-1.5 text-sm bg-stone-700 text-white rounded-full hover:bg-stone-800 transition">Kristen</button>
                <button onclick="setTemplate('hindu')" class="px-4 py-1.5 text-sm bg-stone-700 text-white rounded-full hover:bg-stone-800 transition">Hindu</button>
                <button onclick="setTemplate('ultah')" class="px-4 py-1.5 text-sm bg-stone-700 text-white rounded-full hover:bg-stone-800 transition">Ulang Tahun</button>
                <button onclick="setTemplate('khitan')" class="px-4 py-1.5 text-sm bg-stone-700 text-white rounded-full hover:bg-stone-800 transition">Khitan</button>
            </div>

            {{-- Form Pesan --}}
            <form action="#" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-md font-semibold text-stone-800 mb-2">Pesan Undangan</label>
                    <textarea id="whatsappMessageText" name="message" rows="8" class="w-full p-4 bg-white border border-stone-300 rounded-xl focus:ring-2 focus:ring-stone-500 focus:border-stone-500 transition text-stone-700 leading-relaxed"></textarea>
                </div>

                <button type="submit" class="w-full bg-stone-800 text-white font-bold py-3 px-4 rounded-xl hover:bg-stone-900 transition shadow-lg">
                    Update
                </button>
            </form>

            {{-- Keterangan --}}
            <div class="mt-6 text-xs text-stone-500 bg-stone-200 p-3 rounded-lg">
                <p><span class="font-semibold">Keterangan:</span></p>
                <p>Kode <code class="font-mono">[NAMA-TAMU]</code> = Untuk Generate nama Tamu</p>
                <p>Kode <code class="font-mono">[LINK]</code> = Untuk Generate Undangan</p>
            </div>

            <div class="mt-4 text-right">
                <button onclick="closeWhatsAppModal()" class="px-6 py-2 text-sm text-white bg-stone-500 rounded-lg hover:bg-stone-600 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const whatsappModal = document.getElementById('whatsappModal');
    const whatsappMessageText = document.getElementById('whatsappMessageText');

    const templates = {
        formal: `Yth. Bapak/Ibu/Saudara/i\n[NAMA-TAMU]\n\nDengan hormat,\nKami mengundang Anda untuk menghadiri acara pernikahan kami.\n\nBerikut adalah tautan undangan digital kami:\n[LINK]\n\nAtas kehadiran dan doa restunya, kami ucapkan terima kasih.`,
        islami: `Assalamu'alaikum Warahmatullahi Wabarakatuh\n\nHai [NAMA-TAMU] ✨\n\nDengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk hadir dalam acara pernikahan kami.\n\nBerikut link undangan kami, semua detail acara ada di sini:\n[LINK]\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir.\n\nWassalamu'alaikum Warahmatullahi Wabarakatuh.`,
        kristen: `Shalom [NAMA-TAMU] 🙏\n\n"Demikianlah mereka bukan lagi dua, melainkan satu. Karena itu, apa yang telah dipersatukan Allah, tidak boleh diceraikan manusia."\n(Matius 19:6)\n\nDengan penuh sukacita, kami mengundang Bapak/Ibu/Saudara/i untuk menjadi saksi janji suci pernikahan kami.\n\nInformasi lengkap mengenai acara dapat diakses melalui tautan berikut:\n[LINK]\n\nKehadiran Anda adalah kado terindah bagi kami. Tuhan Yesus memberkati.`,
        hindu: `Om Swastyastu,\n\nHai [NAMA-TAMU],\n\nAtas Asung Kertha Wara Nugraha Ida Sang Hyang Widhi Wasa, kami bermaksud mengundang Bapak/Ibu/Saudara/i pada upacara Manusa Yadnya Pawiwahan (Pernikahan) putra-putri kami.\n\nDetail lengkap acara dapat dilihat pada tautan undangan berikut:\n[LINK]\n\nAtas kehadiran dan doa restunya kami ucapkan terima kasih.\n\nOm Shanti, Shanti, Shanti, Om.`,
        ultah: `Hai [NAMA-TAMU]! 🎉\n\nDalam rangka merayakan hari ulang tahunku, aku mau ngundang kamu buat datang ke pestaku!\n\nSemua info lengkapnya ada di link ini ya:\n[LINK]\n\nJangan lupa datang ya, gak ada kamu gak seru! 😉`,
        khitan: `Assalamu'alaikum Warahmatullahi Wabarakatuh\n\nDengan memohon Rahmat & Ridho Allah SWT, kami mengundang Bapak/Ibu/Saudara/i untuk hadir pada acara Tasyakuran Khitan putra kami.\n\nInformasi selengkapnya terdapat pada undangan digital kami:\n[LINK]\n\nKehadiran dan doa restu Anda sangat berarti bagi kami.\n\nWassalamu'alaikum Warahmatullahi Wabarakatuh.`
    };

    function openWhatsAppModal() {
        setTemplate('islami'); // Set default template saat modal dibuka
        whatsappModal.classList.remove('hidden');
    }

    function closeWhatsAppModal() {
        whatsappModal.classList.add('hidden');
    }

    function setTemplate(templateName) {
        if (templates[templateName]) {
            whatsappMessageText.value = templates[templateName];
        }
    }

    window.addEventListener('click', function(event) {
        if (event.target == whatsappModal) {
            closeWhatsAppModal();
        }
    });
</script>
@endpush
