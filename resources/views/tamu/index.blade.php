@extends('layouts.app')

@section('page_title', 'Tamu')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-slate-800">{{ $event->name ?? 'Nama Event' }}</h1>
        <p class="text-slate-500 mt-1">{{ isset($event->date) ? \Carbon\Carbon::parse($event->date)->isoFormat('dddd, D MMMM YYYY') : 'Tanggal Event' }}</p>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-5">
        <h2 class="text-xl font-semibold text-slate-700">Data Tamu ({{ $guests->total() }} Undangan)</h2>
        <div class="flex items-center gap-2">
            <button id="printQrButton" disabled class="inline-flex items-center justify-center bg-slate-600 text-white px-4 py-2 rounded-lg border border-slate-600 hover:bg-slate-700 transition-colors text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Print QR Terpilih
            </button>
            <button onclick="openImportModal()" class="inline-flex items-center justify-center bg-white text-slate-700 px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-50 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Import Tamu
            </button>
            <a href="{{ route('events.tamu.create', $event) }}" class="inline-flex items-center justify-center bg-amber-500 text-slate-900 px-4 py-2 rounded-lg shadow-md hover:bg-amber-600 transition-colors text-sm font-medium">
                + Tambah Tamu
            </a>
        </div>
    </div>

    <div class="mb-5">
        <input type="text" id="searchInput" placeholder="Cari nama tamu atau kategori..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 transition">
    </div>

    <div class="bg-white shadow-md rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="p-4">
                            <input type="checkbox" id="selectAllCheckbox" class="h-4 w-4 text-amber-600 border-slate-300 rounded focus:ring-amber-500">
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="guestTableBody" class="divide-y divide-slate-100">
                    @forelse ($guests as $guest)
                        <tr id="guest-row-{{$guest->uuid}}" data-guest-uuid="{{ $guest->uuid }}">
                            <td class="p-4">
                                <input type="checkbox" name="guest_ids[]" value="{{ $guest->uuid }}" class="guest-checkbox h-4 w-4 text-amber-600 border-slate-300 rounded focus:ring-amber-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $loop->iteration + ($guests->currentPage() - 1) * $guests->perPage() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium guest-name">{{ $guest->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 guest-affiliation">{{ $guest->affiliation }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                <div class="relative inline-block text-left">
                                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-full text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors dropdown-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                    </button>
                                    <div class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10 hidden dropdown-menu">
                                        <div class="py-1" role="none">
                                            <a href="{{ route('undangan.show', ['event' => $event, 'guest' => $guest]) }}" target="_blank" class="text-slate-700 block px-4 py-2 text-sm hover:bg-slate-100" role="menuitem">Undangan</a>
                                            <button type="button" class="w-full text-left text-slate-700 block px-4 py-2 text-sm hover:bg-slate-100 action-share" role="menuitem" data-url="{{ route('undangan.show', ['event' => $event, 'guest' => $guest]) }}" data-name="{{ $guest->name }}">Share Undangan</button>
                                            <button type="button" class="w-full text-left text-slate-700 block px-4 py-2 text-sm hover:bg-slate-100 action-copy" role="menuitem" data-url="{{ route('undangan.show', ['event' => $event, 'guest' => $guest]) }}" data-name="{{ $guest->name }}">Copy Message</button>
                                            <button type="button" class="w-full text-left text-slate-700 block px-4 py-2 text-sm hover:bg-slate-100 action-edit" role="menuitem" data-uuid="{{ $guest->uuid }}" data-name="{{ $guest->name }}" data-affiliation="{{ $guest->affiliation }}">Edit</button>
                                            <form action="{{ route('events.tamu.destroy', ['event' => $event, 'guest' => $guest]) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-red-50" role="menuitem">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-16 text-slate-500">
                                <p class="font-semibold">Belum ada data tamu.</p>
                                <p class="text-sm mt-1">Silakan klik tombol "+ Tambah Tamu" untuk memulai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($guests->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $guests->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Import Modal --}}
<div id="importModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 md:p-8 w-full max-w-lg m-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800">Import Data Tamu</h2>
            <button onclick="closeImportModal()" class="text-slate-500 hover:text-slate-800 text-3xl leading-none">&times;</button>
        </div>
        <form action="{{ route('events.tamu.import', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-slate-600 mb-2">
                        Unggah file Excel (.xlsx, .xls) atau CSV (.csv). Kolom yang wajib diisi: **Nama** dan **Kategori**.
                    </p>
                    <a href="{{ route('events.tamu.import.template', $event) }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">
                        Unduh Template Format &rarr;
                    </a>
                </div>
                <div>
                    <label for="file" class="block text-sm font-medium text-slate-700">Pilih File</label>
                    <input type="file" name="file" id="file" required class="block w-full text-sm text-slate-500 mt-1 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer"/>
                </div>
            </div>
            <div class="pt-8 flex justify-end gap-3">
                <button type="button" onclick="closeImportModal()" class="bg-white py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-slate-900 bg-amber-500 hover:bg-amber-600">Import Data</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="fixed inset-0 bg-slate-900 bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md m-4">
        <h2 class="text-xl font-semibold mb-4">Edit Tamu</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit-name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="edit-name" name="name" class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500" required>
            </div>
            <div class="mb-4">
                <label for="edit-affiliation" class="block text-sm font-medium text-gray-700">Alamat / Kategori</label>
                <input type="text" id="edit-affiliation" name="affiliation" class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" class="bg-slate-200 text-slate-700 py-2 px-4 rounded-md hover:bg-slate-300" onclick="hideModal()">Batal</button>
                <button type="submit" class="bg-amber-500 text-slate-900 py-2 px-4 rounded-md hover:bg-amber-600">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- DROPDOWN LOGIC ---
     // --- DROPDOWN LOGIC ---
    document.addEventListener('click', function(event) {
        const openDropdowns = document.querySelectorAll('.dropdown-menu:not(.hidden)');
        const toggleButton = event.target.closest('.dropdown-toggle');
        
        openDropdowns.forEach(dropdown => {
            if (!toggleButton || !dropdown.previousElementSibling.isSameNode(toggleButton)) {
                dropdown.classList.add('hidden');
            }
        });

        if (toggleButton) {
            const menu = toggleButton.nextElementSibling;
            menu.classList.toggle('hidden');
        }
    });

    // --- ACTION BUTTONS LOGIC (SHARE, COPY, DELETE) ---
    document.querySelectorAll('.action-share').forEach(button => {
        button.addEventListener('click', (e) => {
            const guestName = e.currentTarget.dataset.name;
            const invitationUrl = e.currentTarget.dataset.url;
            const message = `Kepada Yth. Bapak/Ibu/Saudara/i\n*${guestName}*\n\nDengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Anda untuk hadir dalam acara pernikahan kami.\n\nUntuk detail acara, silakan kunjungi link undangan berikut:\n${invitationUrl}\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir.\nTerima kasih.`;
            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
            window.open(whatsappUrl, '_blank');
        });
    });

    document.querySelectorAll('.action-copy').forEach(button => {
        button.addEventListener('click', (e) => {
            const guestName = e.currentTarget.dataset.name;
            const invitationUrl = e.currentTarget.dataset.url;
            const message = `Kepada Yth. Bapak/Ibu/Saudara/i\n*${guestName}*\n\nDengan memohon rahmat dan ridho Allah SWT, kami bermaksud mengundang Anda untuk hadir dalam acara pernikahan kami.\n\nUntuk detail acara, silakan kunjungi link undangan berikut:\n${invitationUrl}\n\nMerupakan suatu kehormatan dan kebahagiaan bagi kami apabila Anda berkenan hadir.\nTerima kasih.`;
            navigator.clipboard.writeText(message).then(() => {
                Swal.fire({ icon: 'success', title: 'Pesan disalin!', showConfirmButton: false, timer: 1500 });
            }).catch(err => {
                console.error('Error: ' + err);
                alert('Gagal menyalin pesan.');
            });
        });
    });

    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data tamu yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // --- MODAL LOGIC (IMPORT & EDIT) ---
    const importModal = document.getElementById('importModal');
    window.openImportModal = function() {
        importModal.classList.remove('hidden');
        importModal.classList.add('flex');
    }
    window.closeImportModal = function() {
        importModal.classList.add('hidden');
        importModal.classList.remove('flex');
    }
    importModal.addEventListener('click', (e) => {
        if (e.target === importModal) closeImportModal();
    });
    
    const editModal = document.getElementById('editModal');
    document.querySelectorAll('.action-edit').forEach(button => {
        button.addEventListener('click', function() {
            const uuid = this.dataset.uuid;
            const name = this.dataset.name;
            const affiliation = this.dataset.affiliation;
            
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-affiliation').value = affiliation;
            
            const formAction = `/events/{{ $event->uuid }}/tamu/${uuid}`;
            document.getElementById('editForm').action = formAction;
            
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
        });
    });
    
    window.hideModal = function() {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
    }
    editModal.addEventListener('click', (e) => {
        if(e.target === editModal) hideModal();
    });

    // --- SEARCH FUNCTIONALITY ---
    const searchInput = document.getElementById('searchInput');
    const guestTableBody = document.getElementById('guestTableBody');
    const tableRows = guestTableBody.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const filter = searchInput.value.toLowerCase();
        for (let i = 0; i < tableRows.length; i++) {
            const nameCell = tableRows[i].querySelector('.guest-name');
            const affiliationCell = tableRows[i].querySelector('.guest-affiliation');
            if (nameCell && affiliationCell) {
                const nameText = nameCell.textContent || nameCell.innerText;
                const affiliationText = affiliationCell.textContent || affiliationCell.innerText;
                if (nameText.toLowerCase().indexOf(filter) > -1 || affiliationText.toLowerCase().indexOf(filter) > -1) {
                    tableRows[i].style.display = "";
                } else {
                    tableRows[i].style.display = "none";
                }
            }
        }
    });

    // --- SELECT ALL & PRINT SELECTED QR LOGIC ---
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const guestCheckboxes = document.querySelectorAll('.guest-checkbox');
    const printQrButton = document.getElementById('printQrButton');

    function togglePrintButtonState() {
        const anyChecked = Array.from(guestCheckboxes).some(cb => cb.checked);
        printQrButton.disabled = !anyChecked;
    }

    selectAllCheckbox.addEventListener('change', function() {
        guestCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        togglePrintButtonState();
    });

    guestCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else {
                const allChecked = Array.from(guestCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }
            togglePrintButtonState();
        });
    });

    printQrButton.addEventListener('click', function() {
        const selectedGuests = Array.from(guestCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (selectedGuests.length > 0) {
            const printUrl = new URL("{{ route('events.tamu.print_multiple_qr', $event) }}");
            selectedGuests.forEach(uuid => printUrl.searchParams.append('guests[]', uuid));
            
            window.open(printUrl.href, '_blank');
        }
    });

});
</script>
@endpush