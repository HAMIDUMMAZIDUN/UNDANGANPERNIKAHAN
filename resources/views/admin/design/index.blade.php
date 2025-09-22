@extends('admin.layouts.app')
@section('title', 'Editor Desain Undangan Visual')

@push('styles')
<style>
    #invitation-canvas .canvas-item.is-selected {
        outline: 3px solid #f97316;
        outline-offset: 2px;
        position: relative;
    }
    #invitation-canvas .sortable-ghost {
        background-color: #fde8d9;
        border: 2px dashed #f97316;
        opacity: 0.7;
    }
    .canvas-item { cursor: grab; }
    .canvas-item:grabbing { cursor: grabbing; }
    .editor-panel-enter, .editor-panel-leave-to {
        transform: translateX(100%);
        opacity: 0;
    }
    .editor-panel-enter-active, .editor-panel-leave-active {
        transition: all 300ms ease-in-out;
    }

    .delete-btn {
        position: absolute;
        top: -15px;
        right: -15px;
        background-color: #ef4444; 
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        cursor: pointer;
        z-index: 10;
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.2s ease-in-out;
    }
    .is-selected .delete-btn {
        opacity: 1;
        transform: scale(1);
    }
</style>
@endpush

@section('content')
@php
    $formattedDate = now()->isoFormat('dddd, D MMMM YYYY');
    $dummyEvent = (object) [
        'groom_name' => 'Putra',
        'bride_name' => 'Putri',
        'date_formatted' => $formattedDate,
        'photo_url' => 'https://i.ibb.co/VMyB8fN/story-1-AA86-E7-C3.jpg',
        'video_placeholder_url' => 'https://i.ibb.co/zZfT4b2/groom-61-F436-B5.jpg',
        'akad_time' => '08:00 - 10:00 WIB',
        'resepsi_time' => '11:00 - 14:00 WIB',
        'akad_location' => 'Masjid Istiqlal, Jakarta',
        'resepsi_location' => 'Gedung Balai Kartini, Jakarta',
    ];
@endphp

<div x-data="invitationEditor()" x-init="init($el)" data-event='@json($dummyEvent)' class="flex h-[calc(100vh-4rem)] bg-gray-100 font-sans text-gray-800">

    <aside class="w-72 bg-white p-6 shadow-lg flex-shrink-0 overflow-y-auto">
    <h2 class="text-xl font-bold mb-6">Komponen</h2>
    <div class="space-y-3">
        <template x-for="component in availableComponents" :key="component.type">
            <button
                @click="addComponent(component.type)"
                :disabled="isComponentAdded(component.type)"
                class="w-full flex items-start gap-4 p-3 rounded-lg transition-all text-left"
                :class="{
                    'bg-gray-100 hover:bg-orange-100 hover:shadow-sm': !isComponentAdded(component.type),
                    'bg-gray-200 text-gray-400 cursor-not-allowed': isComponentAdded(component.type)
                }"
            >
                <i :class="component.icon" class="text-orange-500 w-6 text-center mt-1 text-lg" :class="{'text-gray-400': isComponentAdded(component.type)}"></i>
                <div>
                    <h3 class="font-semibold" x-text="component.name"></h3>
                    <p class="text-xs text-gray-500" x-text="component.description"></p>
                </div>
            </button>
        </template>
    </div>
</aside>

    <main class="flex-1 flex flex-col p-6 overflow-hidden">
        <header class="flex justify-between items-center mb-6 pb-4 border-b flex-shrink-0">
            <div>
                <h1 class="text-2xl font-bold">Editor Undangan</h1>
                <p class="text-sm text-gray-500">Klik komponen di kiri untuk menambah, klik di area kerja untuk mengedit.</p>
            </div>
            <div class="flex items-center gap-4">
                <button @click="showPreview = true" class="bg-white border border-gray-300 font-bold py-2 px-6 rounded-lg shadow-sm hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i class="fas fa-mobile-alt"></i>
                    <span>Preview</span>
                </button>
                <button @click="saveDesign" class="bg-orange-500 text-white font-bold py-2 px-6 rounded-lg shadow-md hover:bg-orange-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan</span>
                </button>
            </div>
        </header>
        
        <div class="flex-1 bg-gray-200 rounded-lg overflow-y-auto p-4">
            <div id="invitation-canvas" class="max-w-3xl mx-auto bg-white shadow-lg rounded-md min-h-full">
                <template x-if="canvasComponents.length === 0">
                    <div class="p-16 text-center text-gray-400 italic">
                        <i class="fas fa-palette text-4xl mb-4"></i>
                        <p>Area Kerja Anda Masih Kosong</p>
                        <p class="text-sm">Mulailah dengan mengklik komponen di panel kiri</p>
                    </div>
                </template>
                <template x-for="(item, index) in canvasComponents" :key="item.id">
                    <div @click="selectComponent(item.id)" 
                            :class="{ 'is-selected': selectedComponentId === item.id }"
                            :data-id="item.id"
                            class="canvas-item relative group">
                        
                        <button @click.stop="deleteComponent(item.id)" class="delete-btn" title="Hapus Komponen">
                            <i class="fas fa-times text-sm"></i>
                        </button>

                        <div :style="getComponentStyles(item)">
                            <template x-if="item.type === 'cover'">
                                <div class="text-center bg-cover bg-center">
                                    <p class="text-sm tracking-widest">THE WEDDING OF</p>
                                    <img :src="dummyData.photo_url" class="w-32 h-32 object-cover rounded-full mx-auto my-4 border-4 border-white/50">
                                    <h2 class="text-4xl font-serif mt-2" x-text="`${dummyData.bride_name} & ${dummyData.groom_name}`"></h2>
                                    <p class="mt-2 font-semibold" x-text="dummyData.date_formatted"></p>
                                </div>
                            </template>
                            <template x-if="item.type === 'mempelai'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">The Beloved Couple</h3>
                                    <p class="mt-4 text-sm">Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami:</p>
                                </div>
                            </template>
                            <template x-if="item.type === 'video'">
                                <div class="relative h-48 flex items-center justify-center bg-cover bg-center" :style="`background-image: url('${item.styles.videoUrl || dummyData.video_placeholder_url}');`">
                                    <div class="absolute inset-0 bg-black/50"></div>
                                    <i class="fas fa-play-circle text-6xl text-white/70 z-10"></i>
                                </div>
                            </template>
                            <template x-if="item.type === 'acara'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">Save The Date</h3>
                                </div>
                            </template>
                            <template x-if="item.type === 'galeri'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">Our Moments</h3>
                                </div>
                            </template>
                            <template x-if="item.type === 'ucapan'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">Ucapan & RSVP</h3>
                                </div>
                            </template>
                            <template x-if="item.type === 'hadiah'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">Wedding Gift</h3>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <aside x-show="selectedComponent" x-transition:enter="editor-panel-enter-active" x-transition:enter-start="editor-panel-enter" x-transition:leave="editor-panel-leave-active" x-transition:leave-end="editor-panel-leave-to" class="w-96 bg-white p-6 shadow-lg flex-shrink-0 overflow-y-auto">
    <template x-if="selectedComponent">
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold" x-text="selectedComponent.name"></h2>
                <button @click="deselectComponent()" class="text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            
            <div class="space-y-4 border-t pt-4">
                <h3 class="font-semibold">Pengaturan Latar</h3>
                <div>
                    <label class="block text-sm font-medium mb-1">Warna Latar</label>
                    <input type="color" x-model="selectedComponent.styles.backgroundColor" class="w-full h-10 p-1 border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Gambar Latar</label>
                    <input type="file" @change="handleImageUpload($event)" class="w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-orange-50 file:text-orange-700
                        hover:file:bg-orange-100
                    ">
                    <p x-show="selectedComponent.styles.backgroundImage" class="text-xs text-gray-500 mt-2 truncate" x-text="`URL: ${selectedComponent.styles.backgroundImage}`"></p>
                </div>
                
                <template x-if="selectedComponent.type === 'video'">
                    <div class="pt-2">
                        <label class="block text-sm font-medium mb-1">Video Latar (URL)</label>
                        <input type="text" x-model="selectedComponent.styles.videoUrl" placeholder="https://contoh-video.mp4" class="w-full p-2 border border-gray-300 rounded-md text-sm">
                    </div>
                </template>

                <h3 class="font-semibold pt-4 border-t">Teks & Jarak</h3>
                <div>
                    <label class="block text-sm font-medium mb-1">Warna Teks</label>
                    <input type="color" x-model="selectedComponent.styles.color" class="w-full h-10 p-1 border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" x-text="`Jarak Dalam (Padding): ${selectedComponent.styles.padding}px`"></label>
                    <input type="range" min="0" max="150" step="2" x-model="selectedComponent.styles.padding" class="w-full">
                </div>
            </div>
        </div>
    </template>
</aside>

    <div x-show="showPreview" @click.away="showPreview = false" x-cloak class="fixed inset-0 bg-black/50 z-50 flex justify-center items-center p-4">
        <div x-show="showPreview" x-transition class="relative w-full max-w-sm mx-auto">
            <div class="relative mx-auto border-gray-800 bg-gray-800 border-[14px] rounded-[2.5rem] h-[720px] w-[360px] shadow-xl">
                <div class="w-[148px] h-[18px] bg-gray-800 top-0 rounded-b-[1rem] left-1/2 -translate-x-1/2 absolute"></div>
                <div class="rounded-[2rem] overflow-auto w-full h-full bg-white">
                    <template x-for="item in canvasComponents" :key="item.id">
                        <div :style="getComponentStyles(item)">
                            <template x-if="item.type === 'cover'">
                                <div class="text-center bg-cover bg-center">
                                    <p class="text-sm tracking-widest">THE WEDDING OF</p>
                                    <img :src="dummyData.photo_url" class="w-32 h-32 object-cover rounded-full mx-auto my-4 border-4 border-white/50">
                                    <h2 class="text-4xl font-serif mt-2" x-text="`${dummyData.bride_name} & ${dummyData.groom_name}`"></h2>
                                    <p class="mt-2 font-semibold" x-text="dummyData.date_formatted"></p>
                                </div>
                            </template>
                            <template x-if="item.type === 'mempelai'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">The Beloved Couple</h3>
                                    <p class="mt-4 text-sm">Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan putra-putri kami:</p>
                                </div>
                            </template>
                            <template x-if="item.type === 'video'">
                                <div class="relative h-48 flex items-center justify-center bg-cover bg-center" :style="`background-image: url('${item.styles.videoUrl || dummyData.video_placeholder_url}');`">
                                    <div class="absolute inset-0 bg-black/50"></div>
                                    <i class="fas fa-play-circle text-6xl text-white/70 z-10"></i>
                                </div>
                            </template>
                            <template x-if="item.type === 'acara'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">Save The Date</h3>
                                </div>
                            </template>
                            <template x-if="item.type === 'galeri'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">Our Moments</h3>
                                </div>
                            </template>
                            <template x-if="item.type === 'ucapan'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">Ucapan & RSVP</h3>
                                </div>
                            </template>
                            <template x-if="item.type === 'hadiah'">
                                <div class="text-center">
                                    <h3 class="font-serif text-4xl">Wedding Gift</h3>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>

<script>
function invitationEditor() {
    return {
        showPreview: false,
        dummyData: null,
        canvasComponents: [],
        selectedComponentId: null,
        selectedComponent: null, // Properti baru untuk menyimpan komponen yang dipilih
        availableComponents: [
            { type: 'cover', name: 'Cover Utama', description: 'Judul, nama, tanggal.', icon: 'fas fa-book-open' },
            { type: 'mempelai', name: 'Profil Mempelai', description: 'Foto & detail pasangan.', icon: 'fas fa-user-friends' },
            { type: 'video', name: 'Video Latar', description: 'Video sinematik.', icon: 'fas fa-video' },
            { type: 'acara', name: 'Detail Acara', description: 'Waktu & lokasi.', icon: 'fas fa-calendar-check' },
            { type: 'galeri', name: 'Galeri Foto', description: 'Momen pre-wedding.', icon: 'fas fa-images' },
            { type: 'ucapan', name: 'Ucapan & RSVP', description: 'Formulir kehadiran.', icon: 'fas fa-comments' },
            { type: 'hadiah', name: 'Hadiah Pernikahan', description: 'Info rekening/alamat.', icon: 'fas fa-gift' },
        ],
        
        init($el) {
            this.dummyData = JSON.parse($el.dataset.event);

            const canvasEl = document.getElementById('invitation-canvas');
            new Sortable(canvasEl, {
                animation: 150,
                handle: '.canvas-item',
                onEnd: (evt) => {
                    const component = this.canvasComponents.splice(evt.oldIndex, 1)[0];
                    this.canvasComponents.splice(evt.newIndex, 0, component);
                    // Setelah re-order, pastikan selectedComponent tetap terbarui
                    if (this.selectedComponent) {
                         this.selectedComponent = this.canvasComponents.find(c => c.id === this.selectedComponentId);
                    }
                },
            });
        },
        
        isComponentAdded(type) {
            return this.canvasComponents.some(c => c.type === type);
        },
        
        addComponent(type) {
            if (this.isComponentAdded(type)) {
                alert(`Komponen "${this.availableComponents.find(c => c.type === type).name}" sudah ada di kanvas.`);
                return;
            }
        
            const componentData = this.availableComponents.find(c => c.type === type);
            const newComponent = {
                id: Date.now(),
                type: type,
                name: componentData.name,
                styles: {
                    backgroundColor: '#FFFFFF',
                    backgroundImage: '',
                    color: '#334155',
                    padding: 40,
                    videoUrl: '', 
                },
            };
            this.canvasComponents.push(newComponent);
            this.selectComponent(newComponent.id);
        },
        
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                if (this.selectedComponent) {
                    this.selectedComponent.styles.backgroundImage = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        },

        deleteComponent(id) {
            if (!confirm('Anda yakin ingin menghapus komponen ini?')) return;
            this.canvasComponents = this.canvasComponents.filter(c => c.id !== id);
            // Perbarui selectedComponent jika yang dihapus adalah yang terpilih
            if (this.selectedComponentId === id) {
                this.selectedComponentId = null;
                this.selectedComponent = null;
            }
        },

        selectComponent(id) {
            this.selectedComponentId = id;
            this.selectedComponent = this.canvasComponents.find(c => c.id === id);
        },

        deselectComponent() {
            this.selectedComponentId = null;
            this.selectedComponent = null;
        },

        getComponentStyles(item) {
            let styles = {
                backgroundColor: item.styles.backgroundColor,
                color: item.styles.color,
                padding: `${item.styles.padding}px`
            };
            if (item.styles.backgroundImage) {
                styles.backgroundImage = `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('${item.styles.backgroundImage}')`;
                styles.color = '#FFFFFF';
            }
            return styles;
        },

        saveDesign() {
            if (this.canvasComponents.length === 0) {
                alert('Kanvas masih kosong.'); return;
            }
            const designStructure = this.canvasComponents.map(({ id, ...rest }) => rest);
            console.log('Menyimpan struktur:', JSON.stringify(designStructure, null, 2));
            alert('Struktur desain disimpan! (Cek console log untuk melihat datanya)');
        }
    }
}
</script>
@endsection
