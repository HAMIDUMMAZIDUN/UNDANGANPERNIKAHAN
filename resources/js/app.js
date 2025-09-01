import './bootstrap';
import Sortable from 'sortablejs';
window.toggleSidebar = function () {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('translate-x-0');
  sidebar.classList.toggle('-translate-x-full');
};

document.addEventListener('DOMContentLoaded', function () {
    const palette = document.getElementById('palette');
    const canvas = document.getElementById('canvas');
    const saveButton = document.getElementById('saveButton');

    if (palette && canvas) {
        // Inisialisasi Palet (sumber komponen)
        new Sortable(palette, {
            group: {
                name: 'shared',
                pull: 'clone', // 'clone' agar komponen di palet tidak hilang
                put: false
            },
            sort: false, // Jangan biarkan komponen di palet diurutkan
            animation: 150
        });

        // Inisialisasi Kanvas (area tujuan)
        new Sortable(canvas, {
            group: 'shared',
            animation: 150,
            // Anda bisa menambahkan event handler di sini,
            // misalnya untuk menambahkan tombol hapus pada elemen baru
        });
    }

    // Fungsi untuk Tombol Simpan
    if (saveButton) {
        saveButton.addEventListener('click', function () {
            const elements = [];
            canvas.querySelectorAll('[data-type]').forEach(el => {
                const type = el.dataset.type;
                let content = {};
                // Ekstrak konten berdasarkan tipe
                if (type === 'headline') {
                    content.text = el.querySelector('h1').innerText;
                } else if (type === 'paragraph') {
                    content.text = el.querySelector('p').innerText;
                } else if (type === 'image') {
                    content.src = el.querySelector('img')?.src || 'https://via.placeholder.com/400x200';
                    content.alt = el.querySelector('img')?.alt || 'image placeholder';
                }
                elements.push({ type, content });
            });

            // Kirim data ke backend
            const url = this.dataset.url;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ content: elements })
            })
            .then(response => response.json())
            .then(data => alert('Berhasil disimpan!'))
            .catch(error => console.error('Error:', error));
        });
    }
});
