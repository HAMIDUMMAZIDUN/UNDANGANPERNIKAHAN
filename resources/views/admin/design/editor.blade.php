@extends('admin.layouts.app')
@section('title', isset($design) ? 'Edit Design' : 'New Design')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    .editor-container {
        height: calc(100vh - 4rem);
        background: #f1f1f1;
    }
    
    .widget-panel {
        width: 320px;
        background: white;
        border-right: 1px solid #e5e7eb;
    }
    
    .editor-canvas {
        flex: 1;
        background: #f1f1f1;
        position: relative;
    }
    
    .canvas-area {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        min-height: 100vh;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    
    .widget-item {
        padding: 12px;
        margin: 8px 0;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .widget-item:hover {
        background: #f9fafb;
        border-color: #d1d5db;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .canvas-component {
        position: relative;
        border: 2px solid transparent;
        transition: border-color 0.2s;
    }
    
    .canvas-component:hover {
        border-color: #3b82f6;
    }
    
    .canvas-component.selected {
        border-color: #f59e0b;
    }
    
    .component-controls {
        position: absolute;
        top: -30px;
        right: 0;
        background: #f59e0b;
        border-radius: 4px;
        padding: 4px;
        display: none;
        z-index: 10;
    }

    .canvas-component:hover .component-controls,
    .canvas-component.selected .component-controls {
        display: flex;
    }

    .control-btn {
        color: white;
        padding: 4px 8px;
        cursor: pointer;
        border-radius: 2px;
    }

    .control-btn:hover {
        background: rgba(255,255,255,0.2);
    }
    
    .preview-loading {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #f59e0b;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .editor-panel-enter-active {
        transition: all 0.3s ease-out;
    }
    
    .editor-panel-enter-start {
        transform: translateX(100%);
        opacity: 0;
    }
    
    .editor-panel-leave-active {
        transition: all 0.3s ease-in;
    }
    
    .editor-panel-leave-to {
        transform: translateX(100%);
        opacity: 0;
    }
</style>
@endpush

@section('content')
@php
    $dummyEvent = (object) [
        'title' => 'Wedding of John & Jane',
        'bride_name' => 'Jane Smith',
        'groom_name' => 'John Doe',
        'wedding_date' => '2024-12-25',
        'wedding_time' => '10:00',
        'venue_name' => 'Grand Ballroom Hotel',
        'venue_address' => '123 Main Street, City',
        'description' => 'Join us for our special day'
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
                    <h3 class="font-semibold text-gray-700 mb-3" x-text="category.name"></h3>
                    <div class="space-y-2">
                        <template x-for="component in category.components" :key="component.type">
                            <div class="widget-item" 
                                 @click="addComponent(component)"
                                     draggable="true"
                                 @dragstart="dragStart($event, component)">
                                    <div class="flex items-center gap-3">
                                    <i :class="component.icon" class="text-gray-400 text-lg"></i>
                                        <div>
                                        <div x-text="component.name" class="font-medium text-sm"></div>
                                        <div x-text="component.description" class="text-xs text-gray-500"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
    </aside>

    <main class="flex-1 flex flex-col">
        <!-- Editor Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold">Editor: <span x-text="designName || 'Desain Tanpa Judul'"></span></h1>
                <a href="{{ route('admin.design.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Kembali</a>
            </div>
            <div class="flex items-center gap-3">
                <button class="p-2 text-gray-500 hover:text-gray-700" title="Undo">
                    <i class="fas fa-undo"></i>
                </button>
                <button class="p-2 text-gray-500 hover:text-gray-700" title="Redo">
                    <i class="fas fa-redo"></i>
                </button>
                <button @click="togglePreview()" 
                        class="px-4 py-2 rounded-lg text-sm font-medium"
                        :class="{'bg-orange-500 text-white': isPreview, 'bg-gray-100': !isPreview}">
                    <i class="fas" :class="isPreview ? 'fa-edit' : 'fa-eye'"></i>
                    <span x-text="isPreview ? 'Edit Mode' : 'Preview'"></span>
                </button>
                <button @click="checkAndShowSaveModal()" 
                        class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Simpan
                </button>
            </div>
            </div>

        <!-- Canvas Area -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="canvas-area">
                <template x-for="(component, index) in canvasComponents" :key="component.id">
                    <div class="canvas-component"
                         :class="{'selected': selectedComponentId === component.id}"
                         @click.stop="selectComponent(component.id)">
                        <div class="component-controls">
                            <span class="control-btn" @click.stop="moveComponent(index, -1)" title="Move Up">
                            <i class="fas fa-arrow-up"></i>
                        </span>
                            <span class="control-btn" @click.stop="moveComponent(index, 1)" title="Move Down">
                            <i class="fas fa-arrow-down"></i>
                        </span>
                            <span class="control-btn" @click.stop="duplicateComponent(component)" title="Duplicate">
                            <i class="fas fa-copy"></i>
                        </span>
                            <span class="control-btn" @click.stop="removeComponent(component.id)" title="Remove">
                            <i class="fas fa-trash"></i>
                        </span>
                    </div>
                        <div x-html="renderComponent(component)"></div>
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

    <!-- Save Modal -->
    <div x-show="showSaveModal" @keydown.escape.window="showSaveModal = false" x-cloak class="fixed inset-0 bg-black/50 z-50 flex justify-center items-center p-4">
        <div @click.away="showSaveModal = false" class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
            <h2 class="text-xl font-bold mb-2">Simpan Desain</h2>
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
        // Auto-save functionality removed

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
            
            if (existingDesign) {
                this.designId = existingDesign.id;
                this.designName = existingDesign.name;
                this.canvasComponents = existingDesign.structure || [];
            }

            this.setupKeyboardShortcuts();
            this.setupDragAndDrop();
        },

        setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                // Undo: Ctrl/Cmd + Z
                if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    this.undo();
                }
                
                // Redo: Ctrl/Cmd + Shift + Z
                if ((e.ctrlKey || e.metaKey) && e.key === 'z' && e.shiftKey) {
                    e.preventDefault();
                    this.redo();
                }
                
                // Delete selected component
                if (e.key === 'Delete' && this.selectedComponentId) {
                    e.preventDefault();
                    this.removeComponent(this.selectedComponentId);
                }
                
                // Copy component: Ctrl/Cmd + C
                if ((e.ctrlKey || e.metaKey) && e.key === 'c' && this.selectedComponentId) {
                    e.preventDefault();
                    const component = this.canvasComponents.find(c => c.id === this.selectedComponentId);
                    if (component) this.duplicateComponent(component);
                }
            });
        },

        setupDragAndDrop() {
            // This would be implemented for drag and drop functionality
        },

        addComponent(componentType) {
            const newComponent = {
                id: Date.now(),
                type: componentType.type,
                name: componentType.name,
                settings: this.getDefaultSettings(componentType.type)
            };
            
            this.canvasComponents.push(newComponent);
            this.selectComponent(newComponent.id);
            this.saveState();
        },

        getDefaultSettings(type) {
            const defaults = {
                cover: {
                    title: 'The Wedding Of',
                    brideName: this.dummyData.bride_name,
                    groomName: this.dummyData.groom_name,
                    date: this.dummyData.wedding_date,
                    backgroundImage: '',
                    overlayColor: 'rgba(0,0,0,0.4)',
                    textColor: '#ffffff'
                },
                mempelai: {
                    bridePhoto: '',
                    brideName: this.dummyData.bride_name,
                    brideParents: 'Parents of Bride',
                    groomPhoto: '',
                    groomName: this.dummyData.groom_name,
                    groomParents: 'Parents of Groom',
                    layout: 'horizontal'
                },
                acara: {
                    title: 'Wedding Ceremony',
                    date: this.dummyData.wedding_date,
                    time: this.dummyData.wedding_time,
                    venue: this.dummyData.venue_name,
                    address: this.dummyData.venue_address,
                    description: this.dummyData.description
                },
                galeri: {
                    title: 'Our Gallery',
                    photos: []
                },
                video: {
                    title: 'Our Story',
                    youtubeUrl: '',
                    description: 'Watch our love story'
                },
                ucapan: {
                    title: 'Leave a Message',
                    subtitle: 'We would love to hear from you'
                },
                hadiah: {
                    title: 'Wedding Gift',
                    subtitle: 'Your presence is the greatest gift',
                    bankInfo: 'Bank: BCA\nAccount: 1234567890\nName: John & Jane'
                },
                countdown: {
                    title: 'Counting Down',
                    targetDate: this.dummyData.wedding_date,
                    targetTime: this.dummyData.wedding_time
                }
            };
            
            return defaults[type] || {};
        },

        selectComponent(componentId) {
            this.selectedComponentId = componentId;
            this.selectedComponent = this.canvasComponents.find(c => c.id === componentId);
        },

        deselectComponent() {
            this.selectedComponentId = null;
            this.selectedComponent = null;
        },

        removeComponent(componentId) {
            this.canvasComponents = this.canvasComponents.filter(c => c.id !== componentId);
            if (this.selectedComponentId === componentId) {
                this.deselectComponent();
            }
            this.saveState();
        },

        duplicateComponent(component) {
            const clone = {
                ...component,
                id: Date.now(),
                settings: { ...component.settings }
            };
            const index = this.canvasComponents.findIndex(c => c.id === component.id);
            this.canvasComponents.splice(index + 1, 0, clone);
            this.saveState();
        },

        moveComponent(index, direction) {
            const newIndex = index + direction;
            if (newIndex >= 0 && newIndex < this.canvasComponents.length) {
                const component = this.canvasComponents[index];
                this.canvasComponents.splice(index, 1);
                this.canvasComponents.splice(newIndex, 0, component);
                this.saveState();
            }
        },

        renderComponent(component) {
            switch (component.type) {
                case 'cover':
                    return this.renderCover(component);
                case 'mempelai':
                    return this.renderMempelai(component);
                case 'acara':
                    return this.renderAcara(component);
                case 'galeri':
                    return this.renderGaleri(component);
                case 'video':
                    return this.renderVideo(component);
                case 'ucapan':
                    return this.renderUcapan(component);
                case 'hadiah':
                    return this.renderHadiah(component);
                case 'countdown':
                    return this.renderCountdown(component);
                default:
                    return `<div class="p-4 border border-dashed border-gray-300 rounded">Unknown component: ${component.type}</div>`;
            }
        },

        renderCover(component) {
            const bgImage = component.settings.backgroundImage || 'https://placehold.co/1200x800/cccccc/969696?text=Cover+Image';
            return `
                <div class="relative min-h-[80vh] flex items-center justify-center bg-cover bg-center" 
                     style="background-image: url('${bgImage}')">
                    <div class="absolute inset-0" style="background-color: ${component.settings.overlayColor}"></div>
                    <div class="relative text-center p-4" style="color: ${component.settings.textColor}">
                        <p class="text-sm tracking-widest uppercase mb-4">${component.settings.title}</p>
                        <h1 class="font-serif text-5xl md:text-7xl mb-6">
                            ${component.settings.brideName}<br>&<br>${component.settings.groomName}
                        </h1>
                        <p class="text-lg">${component.settings.date}</p>
                    </div>
                </div>`;
        },

        renderMempelai(component) {
            const layoutClass = component.settings.layout === 'horizontal' ? 'md:flex-row' : 'md:flex-col';
            const bridePhoto = component.settings.bridePhoto || 'https://placehold.co/192x192/e2e8f0/cccccc?text=Bride';
            const groomPhoto = component.settings.groomPhoto || 'https://placehold.co/192x192/e2e8f0/cccccc?text=Groom';
            
            return `
                <div class="flex flex-col ${layoutClass} justify-center items-center py-8 gap-8">
                    <div class="text-center">
                        <img src="${bridePhoto}" alt="Bride" class="w-48 h-48 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-md">
                        <h3 class="font-serif text-2xl mb-2">${component.settings.brideName}</h3>
                        <p class="text-gray-600">${component.settings.brideParents}</p>
                    </div>
                    <div class="text-6xl font-serif text-gray-300">&</div>
                    <div class="text-center">
                        <img src="${groomPhoto}" alt="Groom" class="w-48 h-48 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-md">
                        <h3 class="font-serif text-2xl mb-2">${component.settings.groomName}</h3>
                        <p class="text-gray-600">${component.settings.groomParents}</p>
                    </div>
                </div>`;
        },

        renderAcara(component) {
            return `
                <div class="py-16 text-center">
                    <h2 class="text-4xl font-serif mb-8">${component.settings.title}</h2>
                    <div class="max-w-2xl mx-auto">
                        <div class="mb-6">
                            <h3 class="text-2xl font-semibold mb-2">${component.settings.date}</h3>
                            <p class="text-lg text-gray-600">${component.settings.time}</p>
                        </div>
                        <div class="mb-6">
                            <h4 class="text-xl font-semibold mb-2">${component.settings.venue}</h4>
                            <p class="text-gray-600">${component.settings.address}</p>
                        </div>
                        <p class="text-gray-700">${component.settings.description}</p>
                    </div>
                </div>`;
        },

        renderGaleri(component) {
                    return `
                <div class="py-16">
                    <h2 class="text-4xl font-serif text-center mb-8">${component.settings.title}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        </div>
                        <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        </div>
                        <div class="aspect-square bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        </div>
                            </div>
                        </div>`;
        },

        renderVideo(component) {
            return `
                <div class="py-16 text-center">
                    <h2 class="text-4xl font-serif mb-8">${component.settings.title}</h2>
                    <div class="max-w-4xl mx-auto">
                        <div class="aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-play-circle text-6xl text-gray-400"></i>
                        </div>
                        <p class="mt-4 text-gray-600">${component.settings.description}</p>
                    </div>
                </div>`;
        },

        renderUcapan(component) {
                    return `
                <div class="py-16 text-center">
                    <h2 class="text-4xl font-serif mb-4">${component.settings.title}</h2>
                    <p class="text-gray-600 mb-8">${component.settings.subtitle}</p>
                    <div class="max-w-md mx-auto">
                        <div class="space-y-4">
                            <input type="text" placeholder="Your name" class="w-full p-3 border rounded-lg">
                            <textarea placeholder="Your message" class="w-full p-3 border rounded-lg h-24"></textarea>
                            <button class="w-full bg-orange-500 text-white py-3 rounded-lg">Send Message</button>
                            </div>
                            </div>
                        </div>`;
        },

        renderHadiah(component) {
            return `
                <div class="py-16 text-center">
                    <h2 class="text-4xl font-serif mb-4">${component.settings.title}</h2>
                    <p class="text-gray-600 mb-8">${component.settings.subtitle}</p>
                    <div class="max-w-md mx-auto bg-gray-50 p-6 rounded-lg">
                        <pre class="text-sm text-gray-700 whitespace-pre-line">${component.settings.bankInfo}</pre>
                    </div>
                </div>`;
        },

        renderCountdown(component) {
            return `
                <div class="py-16 text-center">
                    <h2 class="text-4xl font-serif mb-8">${component.settings.title}</h2>
                    <div class="flex justify-center gap-4">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-orange-500">00</div>
                            <div class="text-sm text-gray-600">Days</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-orange-500">00</div>
                            <div class="text-sm text-gray-600">Hours</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-orange-500">00</div>
                            <div class="text-sm text-gray-600">Minutes</div>
                        </div>
                    </div>
                </div>`;
        },

        togglePreview() {
            this.isPreviewing = !this.isPreviewing;
            if (this.isPreviewing) {
                this.deselectComponent();
            }
        },

        checkAndShowSaveModal() {
            if (this.canvasComponents.length === 0) {
                this.showNotification('Tambahkan komponen ke kanvas terlebih dahulu', 'error');
                return;
            }
            this.showSaveModal = true;
        },

        async saveDesign() {
            if (!this.designName.trim()) {
                this.showNotification('Please enter a design name', 'error');
                return;
            }

            this.isSaving = true;
            
            try {
                const payload = {
                    name: this.designName,
                    structure: this.canvasComponents,
                    category: 'basic'
                };

                const url = this.designId 
                    ? `/admin/design/${this.designId}`
                    : '{{ route("admin.design.store") }}';

                const response = await fetch(url, {
                    method: this.designId ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                
                if (result.success || result.message) {
                    this.showSaveModal = false;
                    this.hasUnsavedChanges = false;
                    
                    if (!this.designId && result.design) {
                        this.designId = result.design.id;
                    }
                } else {
                    throw new Error(result.message || 'Failed to save design');
                }
            } catch (error) {
                console.error('Save error:', error);
            } finally {
                this.isSaving = false;
            }
        },

        saveState() {
            this.history.past.push(JSON.stringify(this.canvasComponents));
            this.history.future = [];
            this.hasUnsavedChanges = true;
        },

        undo() {
            if (this.history.past.length > 0) {
                this.history.future.push(JSON.stringify(this.canvasComponents));
                this.canvasComponents = JSON.parse(this.history.past.pop());
                this.deselectComponent();
            }
        },

        redo() {
            if (this.history.future.length > 0) {
                this.history.past.push(JSON.stringify(this.canvasComponents));
                this.canvasComponents = JSON.parse(this.history.future.pop());
                this.deselectComponent();
            }
        },

        handleBeforeUnload(event) {
            if (this.hasUnsavedChanges) {
                event.preventDefault();
                event.returnValue = '';
            }
        },

        showNotification(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white transform transition-all duration-300 ${
                type === 'error' ? 'bg-red-500' : 
                type === 'success' ? 'bg-green-500' : 
                'bg-blue-500'
            }`;
            
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    };
}
</script>
@endpush
