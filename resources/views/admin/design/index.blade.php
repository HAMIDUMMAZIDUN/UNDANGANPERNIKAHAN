@extends('admin.layouts.app')
@section('title', isset($design) ? 'Edit Desain' : 'Buat Desain Baru')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css" rel="stylesheet">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;500;600&display=swap');
    :root { --primary-color: #f97316; }
    .font-serif { font-family: 'Playfair Display', serif; }
    .font-sans { font-family: 'Poppins', sans-serif; }
    #invitation-canvas .is-selected { outline: 3px solid var(--primary-color); outline-offset: 3px; }
    #invitation-canvas .sortable-ghost { background-color: #fde8d9; border: 2px dashed var(--primary-color); }
    .canvas-item { cursor: grab; }
    .canvas-item:grabbing { cursor: grabbing; }
    .editor-panel-enter-active, .editor-panel-leave-active { transition: all 300ms ease-in-out; }
    .editor-panel-enter-start, .editor-panel-leave-to { transform: translateX(100%); opacity: 0; }
    .delete-btn { position: absolute; top: -15px; right: -15px; background-color: #ef4444; color: white; width: 30px; height: 30px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); cursor: pointer; z-index: 10; opacity: 0; transform: scale(0.8); transition: all 0.2s ease-in-out; }
    .is-selected .delete-btn { opacity: 1; transform: scale(1); }
    .spinner { border: 2px solid #f3f3f3; border-top: 2px solid var(--primary-color); border-radius: 50%; width: 16px; height: 16px; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    
    
    .preview-loading {
        position: fixed;
        inset: 0;
        background: rgba(255,255,255,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
    }
</style>
@endpush

@section('content')
@php
    $formattedDate = now()->isoFormat('dddd, D MMMM YYYY');
    $dummyEvent = (object) [
        'groom_name' => 'Putra', 'groom_parents' => 'Bapak Budi & Ibu Wati', 'bride_name' => 'Putri', 'bride_parents' => 'Bapak Santoso & Ibu Lestari', 'date_formatted' => $formattedDate,
        'cover_photo_url' => 'https://images.unsplash.com/photo-1597861405922-26b21c43c4f9?q=85&fm=jpg&w=1200', 'couple_photo_url' => 'https://images.unsplash.com/photo-1529602264082-036993544063?q=85&fm=jpg&w=1200',
        'video_placeholder_url' => 'https://images.unsplash.com/photo-1515934323957-66a7a092a452?q=85&fm=jpg&w=1200', 'akad_time' => '08:00 - 10:00 WIB', 'resepsi_time' => '11:00 - 14:00 WIB',
        'akad_location' => 'Masjid Istiqlal, Jakarta', 'resepsi_location' => 'Gedung Balai Kartini, Jakarta',
        'gallery_photos' => [['url' => 'https://images.unsplash.com/photo-1523438943922-386d3header-e439fe?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1606992257321-2527ac312a02?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1532712938310-34cb39825785?q=85&fm=jpg&w=800'], ['url' => 'https://images.unsplash.com/photo-1546193435-99805a99859f?q=85&fm=jpg&w=800'],]
    ];
    $existingDesign = isset($design) ? $design : null;
@endphp

<div x-data="invitationEditor()" 
     x-init="init($el, @json($existingDesign ?? null))"
     data-event='@json($dummyEvent)'
     @before-unload.window="handleBeforeUnload($event)"
     class="flex h-[calc(100vh-4rem)] bg-gray-100 font-sans text-gray-800">

    <!-- Preview loading overlay -->
    <div x-show="isPreviewing"
         x-transition
         class="preview-loading">
        <div class="text-center">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-gray-600">Memuat Preview...</p>
        </div>
    </div>

    <aside class="w-80 bg-white p-6 shadow-lg flex-shrink-0 overflow-y-auto">
        <h2 class="text-xl font-bold mb-2">Widget Undangan</h2>
        <p class="text-xs text-gray-500 mb-6">Klik atau seret widget ke area kerja.</p>
        <div class="space-y-6">
            <template x-for="category in componentCategories" :key="category.name">
                <div>
                    <h3 class="font-semibold text-sm text-gray-500 uppercase tracking-wider mb-3" x-text="category.name"></h3>
                    <div class="space-y-2">
                        <template x-for="component in category.components" :key="component.type">
                            <button @click="addComponent(component.type)" :disabled="isComponentAdded(component.type)" 
                                    class="w-full flex items-start gap-4 p-3 rounded-lg transition-all text-left disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed bg-gray-50 hover:bg-orange-100 hover:shadow-sm">
                                <i :class="[component.icon, isComponentAdded(component.type) ? 'text-gray-400' : 'text-orange-500']" class="w-6 text-center mt-1 text-lg"></i>
                                <div>
                                    <h3 class="font-semibold" x-text="component.name"></h3>
                                    <p class="text-xs text-gray-500" x-text="component.description"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </aside>

    <main class="flex-1 flex flex-col p-6 overflow-hidden">
        <header class="flex justify-between items-center mb-6 pb-4 border-b flex-shrink-0">
            <div>
                <h1 class="text-2xl font-bold" x-text="`Editor: ${designName || 'Desain Tanpa Judul'}`"></h1>
                <div class="flex items-center gap-4 mt-1">
                    <a href="{{ route('admin.design.saved_designs') }}" class="text-sm text-orange-500 hover:underline">Kembali</a>
                    <span x-show="hasUnsavedChanges" class="text-sm text-amber-600 font-semibold flex items-center gap-1">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        Perubahan belum disimpan
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button @click="undo()" :disabled="history.past.length <= 1" class="disabled:text-gray-300 text-gray-500 hover:text-orange-500" title="Undo (Ctrl+Z)"><i class="fas fa-undo"></i></button>
                <button @click="redo()" :disabled="history.future.length === 0" class="disabled:text-gray-300 text-gray-500 hover:text-orange-500" title="Redo (Ctrl+Shift+Z)"><i class="fas fa-redo"></i></button>
                <button @click="openPreview()" :disabled="isPreviewing" class="bg-white border border-gray-300 font-bold py-2 px-6 rounded-lg shadow-sm hover:bg-gray-100 transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-wait">
                    <i class="fas fa-eye"></i>
                    <span x-text="isPreviewing ? 'Memuat...' : 'Preview'"></span>
                </button>
                <button @click="showSaveModal = true" class="bg-orange-500 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:bg-orange-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i><span>Simpan</span>
                </button>
            </div>
        </header>
        
        <div class="flex-1 bg-gray-200 rounded-lg overflow-y-auto p-4 sm:p-8 flex justify-center">
            <div id="invitation-canvas" class="w-full max-w-3xl bg-white shadow-lg rounded-md min-h-full space-y-4 py-8">
                <template x-if="canvasComponents.length === 0">
                    <div class="p-8 text-center text-gray-400">
                        <i class="fas fa-palette text-5xl mb-4"></i>
                        <p class="text-lg font-medium">Area Kerja Kosong</p>
                        <p class="text-sm">Pilih widget di panel kiri untuk memulai</p>
                    </div>
                </template>
                <template x-for="(item, index) in canvasComponents" :key="item.id">
                    <div @click="selectComponent(item.id)" :class="{ 'is-selected': selectedComponentId === item.id }" :data-id="item.id" class="canvas-item group mx-4 rounded-md">
                        <button @click.stop="deleteComponent(item.id)" class="delete-btn" title="Hapus"><i class="fas fa-times text-sm"></i></button>
                        <div x-html="renderComponent(item)"></div>
                    </div>
                </template>
            </div>
        </div>
    </main>

    <aside x-show="selectedComponent" 
           x-transition:enter="editor-panel-enter-active" x-transition:enter-start="editor-panel-enter-start"
           x-transition:leave="editor-panel-leave-active" x-transition:leave-to="editor-panel-leave-to" 
           class="w-96 bg-white p-6 shadow-lg flex-shrink-0 overflow-y-auto">
        <template x-if="selectedComponent">
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold" x-text="selectedComponent.name"></h2>
                    <button @click="deselectComponent()" class="text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                </div>
            </div>
        </template>
    </aside>

    <div x-show="showSaveModal" @keydown.escape.window="showSaveModal = false" x-cloak class="fixed inset-0 bg-black/50 z-50 flex justify-center items-center p-4">
        <div @click.away="showSaveModal = false" class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4">Simpan Desain</h2>
            <p class="text-sm text-gray-600 mb-6">Beri nama desain ini agar mudah ditemukan nanti.</p>
            <div>
                <label for="design_name" class="block text-sm font-medium mb-1">Nama Desain</label>
                <input type="text" id="design_name" x-model="designName" placeholder="Contoh: Desain Elegan Mawar" class="w-full p-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="flex justify-end gap-4 mt-8">
                <button @click="showSaveModal = false" class="bg-gray-200 text-gray-800 font-bold py-2 px-6 rounded-lg hover:bg-gray-300">Batal</button>
                <button @click="saveDesign()" :disabled="isSaving || !designName.trim()" class="bg-orange-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-orange-600 disabled:bg-orange-300 flex items-center gap-2">
                    <span x-show="isSaving" class="spinner"></span>
                    <span x-text="isSaving ? 'Menyimpan...' : 'Simpan'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function invitationEditor() {
    return {
        // Remove these properties
        /*
        */

        // Keep existing properties
        isSaving: false,
        isPreviewing: false,
        showSaveModal: false,
        hasUnsavedChanges: false,
        lastSavedState: '',
        
        // Existing properties
        designId: null,
        designName: '',
        dummyData: null,
        canvasComponents: [],
        selectedComponentId: null,
        selectedComponent: null,
        activeTab: 'content',
        history: { past: [], future: [] },
        
        componentCategories: [
            { name: 'Dasar', components: [
                { type: 'cover', name: 'Cover Utama', description: 'Halaman pembuka undangan.', icon: 'fas fa-book-open' },
                { type: 'mempelai', name: 'Profil Mempelai', description: 'Detail kedua pasangan.', icon: 'fas fa-user-friends' },
                { type: 'acara', name: 'Detail Acara', description: 'Waktu & lokasi pernikahan.', icon: 'fas fa-calendar-check' },
            ]},
            { name: 'Media', components: [
                { type: 'galeri', name: 'Galeri Foto', description: 'Album momen pre-wedding.', icon: 'fas fa-images' },
                { type: 'video', name: 'Video Cinematic', description: 'Sematkan video dari YouTube.', icon: 'fas fa-video' },
            ]},
            { name: 'Interaktif', components: [
                { type: 'ucapan', name: 'Ucapan & RSVP', description: 'Formulir kehadiran tamu.', icon: 'fas fa-comments' },
                { type: 'hadiah', name: 'Hadiah Pernikahan', description: 'Informasi amplop digital.', icon: 'fas fa-gift' },
                { type: 'countdown', name: 'Hitung Mundur', description: 'Timer menuju hari H.', icon: 'fas fa-stopwatch' },
            ]}
        ],

        init($el, existingDesign = null) {
            // Get the element that has the data-event attribute
            const element = $el || document.querySelector('[data-event]');
            
            if (!element) {
                console.error('Element not found');
                this.dummyData = {};
                return;
            }
            
            try {
                this.dummyData = JSON.parse(element.dataset.event || '{}');
            } catch (e) {
                console.error('Error parsing event data:', e);
                this.dummyData = {};
            }
            this.initializeSortable();
            this.setupKeyboardShortcuts();
            
            if (existingDesign) {
                this.designId = existingDesign.id;
                this.designName = existingDesign.name;
                if (existingDesign.structure && typeof existingDesign.structure === 'string') {
                    this.canvasComponents = JSON.parse(existingDesign.structure)
                        .map((item, index) => ({ ...item, id: Date.now() + index }));
                } else if (Array.isArray(existingDesign.structure)) {
                    this.canvasComponents = existingDesign.structure
                        .map((item, index) => ({ ...item, id: Date.now() + index }));
                }
            }
            
            this.saveState(this.canvasComponents);
            this.lastSavedState = JSON.stringify(this.canvasComponents);
            
            // Simple change tracking without autosave
            this.$watch('canvasComponents', (val) => {
                this.hasUnsavedChanges = JSON.stringify(val) !== this.lastSavedState;
            }, { deep: true });
        },

        initializeSortable() {
            new Sortable(document.getElementById('invitation-canvas'), {
                animation: 150,
                handle: '.canvas-item',
                onEnd: (evt) => {
                    this.saveState();
                    const component = this.canvasComponents.splice(evt.oldIndex, 1)[0];
                    this.canvasComponents.splice(evt.newIndex, 0, component);
                }
            });
        },
        
        setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'z' && (e.metaKey || e.ctrlKey)) {
                    e.preventDefault();
                    e.shiftKey ? this.redo() : this.undo();
                }
            });
        },

        addComponent(type) {
            if (this.isComponentAdded(type)) return;
            this.saveState();
            const defaults = this.getComponentDefaults(type);
            const newComponent = { id: Date.now(), type, ...defaults };
            this.canvasComponents.push(newComponent);
            this.selectComponent(newComponent.id);
        },
        
        deleteComponent(id) {
            Swal.fire({
                title: 'Hapus Komponen?', text: "Tindakan ini tidak bisa dibatalkan.", icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal',
                confirmButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.saveState();
                    this.canvasComponents = this.canvasComponents.filter(c => c.id !== id);
                    if (this.selectedComponentId === id) this.deselectComponent();
                }
            });
        },

        selectComponent(id) {
            this.selectedComponentId = id;
            this.selectedComponent = this.canvasComponents.find(c => c.id === id);
            this.activeTab = 'content';
        },

        deselectComponent() {
            this.selectedComponentId = null;
            this.selectedComponent = null;
        },

        saveState(state = this.canvasComponents) {
            if(JSON.stringify(state) === this.history.past[this.history.past.length - 1]) return;
            this.history.past.push(JSON.stringify(state));
            this.history.future = [];
            if (this.history.past.length > 30) this.history.past.shift();
        },

        undo() {
            if (this.history.past.length <= 1) return;
            this.history.future.push(this.history.past.pop());
            const previousState = this.history.past[this.history.past.length - 1];
            this.canvasComponents = JSON.parse(previousState);
            this.hasUnsavedChanges = true;
        },
        
        redo() {
            if (this.history.future.length === 0) return;
            const nextState = this.history.future.pop();
            this.history.past.push(nextState);
            this.canvasComponents = JSON.parse(nextState);
            this.hasUnsavedChanges = true;
        },

        async saveDesign() {
            if (!this.designName.trim()) {
                this.showNotification('error', 'Nama desain tidak boleh kosong.');
                this.showSaveModal = true;
                return;
            }

            if (this.canvasComponents.length === 0) {
                this.showNotification('error', 'Kanvas tidak boleh kosong.');
                return;
            }

            try {
                this.isSaving = true;
                
                const payload = {
                    name: this.designName || 'Desain Tanpa Judul',
                    structure: JSON.stringify(this.canvasComponents.map(({ id, ...rest }) => rest))
                };

                const url = this.designId ? 
                    `/admin/design/${this.designId}` : 
                    '{{ route("admin.design.store") }}';
                    
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-HTTP-Method-Override': this.designId ? 'PUT' : 'POST',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Terjadi kesalahan');

                if(data.redirect_url) {
                    setTimeout(() => window.location.href = data.redirect_url, 1500);
                }

                this.lastSavedState = JSON.stringify(this.canvasComponents);
                this.hasUnsavedChanges = false;

            } catch (error) {
                this.showNotification('error', `Gagal menyimpan: ${error.message}`);
                throw error;
            } finally {
                this.isSaving = false;
                this.showSaveModal = false;
            }
        },

        handleBeforeUnload(event) {
            if (this.hasUnsavedChanges) {
                event.preventDefault();
                event.returnValue = '';
            }
        },

        async openPreview() {
            this.isPreviewing = true;
            const previewTab = window.open('', '_blank');
            previewTab.document.write('<body><p>Memuat preview...</p></body>');
            const designStructure = this.canvasComponents.map(({ id, ...rest }) => rest);
            const payload = { name: this.designName || 'Preview Desain', structure: JSON.stringify(designStructure) };
            try {
                const response = await fetch('{{ route("admin.design.live_preview") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'text/html', }, body: JSON.stringify(payload) });
                if (!response.ok) throw new Error(`Server error: ${response.statusText}`);
                const html = await response.text();
                previewTab.document.open();
                previewTab.document.write(html);
                previewTab.document.close();
            } catch (error) {
                console.error('Error generating preview:', error);
                previewTab.document.write(`<body style="font-family: sans-serif;"><h3 style="color: red;">Gagal memuat preview</h3><p>${error.message}</p></body>`);
            } finally {
                this.isPreviewing = false;
            }
        },
        
        isComponentAdded(type) { return this.canvasComponents.some(c => c.type === type); },

        getComponentDefaults(type) {
            const componentInfo = this.componentCategories.flatMap(c => c.components).find(c => c.type === type);
            if (!componentInfo) {
                console.error(`Definisi untuk komponen tipe "${type}" tidak ditemukan.`);
                return { 
                    name: 'Komponen Tidak Dikenal', 
                    styles: { backgroundColor: '#fecaca', padding: 48, color: '#991b1b' }, 
                    data: {} 
                };
            }

            const defaults = {
                name: componentInfo.name,
                styles: { backgroundColor: '#FFFFFF', backgroundImage: '', color: '#334155', padding: 48 },
                data: {}
            };

            switch(type) {
                case 'cover':
                    defaults.data = { openingText: 'THE WEDDING OF', brideName: this.dummyData.bride_name, groomName: this.dummyData.groom_name, date: this.dummyData.date_formatted };
                    defaults.styles.backgroundImage = this.dummyData.cover_photo_url;
                    defaults.styles.padding = 80;
                    defaults.styles.color = '#FFFFFF';
                    break;
                case 'mempelai':
                    defaults.data = { introText: 'Dengan memohon rahmat dan ridho Allah SWT...', brideName: this.dummyData.bride_name, brideBio: 'Putri dari Pasangan', brideParents: this.dummyData.bride_parents, bridePhotoUrl: this.dummyData.couple_photo_url, groomName: this.dummyData.groom_name, groomBio: 'Putra dari Pasangan', groomParents: this.dummyData.groom_parents, groomPhotoUrl: this.dummyData.video_placeholder_url };
                    break;
                case 'acara':
                    defaults.data = { akadDate: this.dummyData.date_formatted, akadTime: this.dummyData.akad_time, akadLocation: this.dummyData.akad_location, resepsiDate: this.dummyData.date_formatted, resepsiTime: this.dummyData.resepsi_time, resepsiLocation: this.dummyData.resepsi_location };
                    break;
                case 'galeri':
                    defaults.data = { photoList: this.dummyData.gallery_photos.map(p => p.url).join('\n') };
                    break;
            }
            return defaults;
        },
        
        renderComponent(item) {
            const data = { ...this.getComponentDefaults(item.type).data, ...item.data };
            const paddingValue = item.styles.padding ?? 48;
            let content = '';

            switch(item.type) {
                case 'cover': content = `<div class="min-h-[70vh] flex flex-col justify-center items-center"><p class="text-sm tracking-widest uppercase">${data.openingText}</p><h2 class="text-4xl md:text-6xl font-serif my-6">${data.brideName} & ${data.groomName}</h2><p class="font-semibold text-lg">${data.date}</p></div>`; break;
                case 'mempelai': content = `<p class="text-base mb-10 max-w-xl mx-auto leading-relaxed">${data.introText}</p><div class="flex flex-col md:flex-row justify-center items-center gap-8 md:gap-12"><div class="w-64"><img src="${data.bridePhotoUrl}" class="w-48 h-48 object-cover rounded-full mx-auto border-4 border-white/70 shadow-lg"><h3 class="text-3xl font-serif mt-6">${data.brideName}</h3><p class="text-sm mt-2 font-medium">${data.brideBio}</p><p class="text-sm">${data.brideParents}</p></div><span class="text-6xl font-serif my-4 md:my-0">&</span><div class="w-64"><img src="${data.groomPhotoUrl}" class="w-48 h-48 object-cover rounded-full mx-auto border-4 border-white/70 shadow-lg"><h3 class="text-3xl font-serif mt-6">${data.groomName}</h3><p class="text-sm mt-2 font-medium">${data.groomBio}</p><p class="text-sm">${data.groomParents}</p></div></div>`; break;
                case 'acara': content = `<h2 class="font-serif text-4xl mb-10">Save The Date</h2><div class="flex flex-col md:flex-row justify-center gap-8 text-left"><div class="border-2 border-current/20 p-6 rounded-lg flex-1 backdrop-blur-sm bg-white/10"><h3 class="font-serif text-3xl">Akad Nikah</h3><div class="mt-4 space-y-2"><p><i class="fa-solid fa-calendar-alt w-6"></i><span class="font-semibold">${data.akadDate}</span></p><p><i class="fa-solid fa-clock w-6"></i>${data.akadTime}</p><p class="mt-2"><i class="fa-solid fa-map-marker-alt w-6"></i>${data.akadLocation}</p></div></div><div class="border-2 border-current/20 p-6 rounded-lg flex-1 backdrop-blur-sm bg-white/10"><h3 class="font-serif text-3xl">Resepsi</h3><div class="mt-4 space-y-2"><p><i class="fa-solid fa-calendar-alt w-6"></i><span class="font-semibold">${data.resepsiDate}</span></p><p><i class="fa-solid fa-clock w-6"></i>${data.resepsiTime}</p><p class="mt-2"><i class="fa-solid fa-map-marker-alt w-6"></i>${data.resepsiLocation}</p></div></div></div>`; break;
                case 'galeri': const photos = data.photoList ? data.photoList.split('\n').filter(url => url.trim() !== '') : []; let galleryHtml = `<h2 class="font-serif text-4xl mb-10">Our Moments</h2>`; if (photos.length > 0) { galleryHtml += `<div class="grid grid-cols-2 md:grid-cols-3 gap-4">`; photos.forEach(url => { galleryHtml += `<div class="aspect-square bg-gray-300/50 rounded-lg bg-cover bg-center" style="background-image: url('${url.trim()}')"></div>`; }); galleryHtml += `</div>`; } else { galleryHtml += `<p class="text-sm italic text-current/70">Galeri foto akan ditampilkan di sini.</p>`; } content = galleryHtml; break;
                case 'video': content = `<h2 class="font-serif text-4xl mb-10">Our Love Story</h2><div class="aspect-video bg-gray-900 rounded-lg flex items-center justify-center text-white/50 relative overflow-hidden group"><img src="${this.dummyData.video_placeholder_url}" class="absolute w-full h-full object-cover opacity-50"><div class="z-10 text-center"><i class="fas fa-play-circle text-6xl"></i><p class="mt-2 font-semibold">Tonton Video Kami</p></div></div>`; break;
                case 'ucapan': content = `<h2 class="font-serif text-4xl mb-10">Ucapan & RSVP</h2><div class="max-w-md mx-auto text-left bg-white/10 backdrop-blur-sm p-6 rounded-lg"><p class="text-sm text-current/80">Placeholder formulir RSVP akan ditampilkan di sini.</p></div>`; break;
                case 'hadiah': content = `<h2 class="font-serif text-4xl mb-10">Wedding Gift</h2><p class="max-w-xl mx-auto mb-8 text-sm">Doa restu Anda merupakan karunia yang sangat berarti bagi kami.</p><div class="max-w-md mx-auto space-y-4"><p class="text-sm text-current/80">Placeholder hadiah digital akan ditampilkan di sini.</p></div>`; break;
                case 'countdown': content = `<h2 class="font-serif text-4xl mb-6">Menuju Hari Bahagia</h2><div class="flex justify-center gap-4 md:gap-8"><div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg w-24"><p class="text-4xl font-bold">00</p><p class="text-xs uppercase">Hari</p></div><div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg w-24"><p class="text-4xl font-bold">00</p><p class="text-xs uppercase">Jam</p></div><div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg w-24"><p class="text-4xl font-bold">00</p><p class="text-xs uppercase">Menit</p></div><div class="bg-white/10 backdrop-blur-sm p-4 rounded-lg w-24"><p class="text-4xl font-bold">00</p><p class="text-xs uppercase">Detik</p></div></div>`; break;
                default: content = `<p class="text-red-700 bg-red-100 p-4 rounded-md">Komponen tidak dikenal: ${item.type}</p>`;
            }

            let styleString = `background-color: ${item.styles.backgroundColor}; color: ${item.styles.color}; text-align: center; background-size: cover; background-position: center;`;
            if (item.styles.backgroundImage) {
                styleString += `background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('${item.styles.backgroundImage}'); color: #FFFFFF;`;
            }
            return `<div style="${styleString}"><div class="px-6 sm:px-8" style="padding-top: ${paddingValue}px; padding-bottom: ${paddingValue}px;">${content}</div></div>`;
        },

        showNotification(type, message) {
            Swal.fire({
                icon: type,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        },
    };
}
</script>
@endpush