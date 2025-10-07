@extends('admin.layouts.app')
@section('title', isset($design) ? 'Edit Design' : 'New Design')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>
    /* Elementor-like styles */
    .elementor-panel {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        width: 280px;
        background: #ffffff;
        z-index: 100;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        overflow-y: auto;
    }

    .elementor-canvas {
        margin-left: 280px;
        padding: 20px;
        min-height: 100vh;
        background: #f1f1f1;
    }

    .element-item {
        padding: 12px;
        margin: 4px 0;
        background: #fff;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #eee;
    }

    .element-item:hover {
        background: #f7f7f7;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .settings-panel {
        position: fixed;
        right: 0;
        top: 0;
        bottom: 0;
        width: 300px;
        background: #ffffff;
        z-index: 100;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        transform: translateX(100%);
        transition: transform 0.3s;
    }

    .settings-panel.active {
        transform: translateX(0);
    }

    .canvas-element {
        position: relative;
        margin: 10px 0;
        background: #fff;
        border-radius: 4px;
        border: 1px solid transparent;
    }

    .canvas-element.selected {
        border-color: #0073aa;
    }

    .element-controls {
        position: absolute;
        top: -25px;
        right: 5px;
        background: #0073aa;
        border-radius: 4px;
        padding: 2px;
        display: none;
        z-index: 10;
    }

    .canvas-element:hover .element-controls,
    .canvas-element.selected .element-controls {
        display: flex;
    }

    .control-btn {
        color: white;
        padding: 4px 8px;
        cursor: pointer;
    }

    .control-btn:hover {
        background: rgba(255,255,255,0.1);
        border-radius: 2px;
    }
</style>
@endpush

@section('content')
<div x-data="elementorEditor()" x-init="init()">
    <!-- Left Panel (Elements) -->
    <div class="elementor-panel">
        <div class="p-4">
            <h2 class="text-xl font-bold mb-4">Elements</h2>
            <div class="space-y-4">
                <template x-for="category in categories" :key="category.name">
                    <div>
                        <h3 class="font-medium text-gray-600 mb-2" x-text="category.name"></h3>
                        <div class="space-y-1">
                            <template x-for="element in category.elements" :key="element.type">
                                <div class="element-item" 
                                     @click="addElement(element)"
                                     draggable="true"
                                     @dragstart="dragStart($event, element)">
                                    <div class="flex items-center gap-3">
                                        <i :class="element.icon" class="text-gray-400 fa-fw"></i>
                                        <div>
                                            <div x-text="element.name" class="font-medium"></div>
                                            <div x-text="element.description" class="text-xs text-gray-500"></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Main Canvas -->
    <div class="elementor-canvas" @click="deselectElement()">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-sm p-8">
            <template x-for="(element, index) in elements" :key="element.id">
                <div class="canvas-element"
                     :class="{'selected': selectedElement?.id === element.id}"
                     @click.stop="selectElement(element)">
                    <div class="element-controls">
                        <span class="control-btn" @click.stop="moveElement(index, -1)" title="Move Up">
                            <i class="fas fa-arrow-up"></i>
                        </span>
                        <span class="control-btn" @click.stop="moveElement(index, 1)" title="Move Down">
                            <i class="fas fa-arrow-down"></i>
                        </span>
                        <span class="control-btn" @click.stop="duplicateElement(element)" title="Duplicate">
                            <i class="fas fa-copy"></i>
                        </span>
                        <span class="control-btn" @click.stop="removeElement(element)" title="Remove">
                            <i class="fas fa-trash"></i>
                        </span>
                    </div>
                    <div x-html="renderElement(element)"></div>
                </div>
            </template>
        </div>
    </div>

    <!-- Right Panel (Settings) -->
    <div class="settings-panel" :class="{'active': selectedElement}">
        <template x-if="selectedElement">
            <div class="h-full flex flex-col">
                <div class="p-4 border-b">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-lg" x-text="selectedElement.name"></h3>
                        <button @click="deselectElement()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <button @click="settingsTab = 'content'" 
                                :class="{'bg-orange-500 text-white': settingsTab === 'content', 'bg-gray-100': settingsTab !== 'content'}"
                                class="px-4 py-2 rounded-lg text-sm font-medium">
                            Content
                        </button>
                        <button @click="settingsTab = 'style'"
                                :class="{'bg-orange-500 text-white': settingsTab === 'style', 'bg-gray-100': settingsTab !== 'style'}"
                                class="px-4 py-2 rounded-lg text-sm font-medium">
                            Style
                        </button>
                        <button @click="settingsTab = 'advanced'"
                                :class="{'bg-orange-500 text-white': settingsTab === 'advanced', 'bg-gray-100': settingsTab !== 'advanced'}"
                                class="px-4 py-2 rounded-lg text-sm font-medium">
                            Advanced
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
                    <div x-show="settingsTab === 'content'" class="space-y-4">
                        <!-- Dynamic content settings based on element type -->
                    </div>
                    <div x-show="settingsTab === 'style'" class="space-y-4">
                        <!-- Common style settings -->
                    </div>
                    <div x-show="settingsTab === 'advanced'" class="space-y-4">
                        <!-- Advanced settings like margins, padding, etc -->
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
@endsection

@push('scripts')
<script>
function elementorEditor() {
    return {
        elements: [],
        selectedElement: null,
        settingsTab: 'content',
        categories: [
            {
                name: 'Wedding Components',
                elements: [
                    {
                        type: 'cover',
                        name: 'Cover Section',
                        icon: 'fas fa-image',
                        description: 'Main wedding cover with couple names',
                        defaultSettings: {
                            title: 'The Wedding Of',
                            brideName: 'Bride Name',
                            groomName: 'Groom Name',
                            date: 'Wedding Date',
                            backgroundImage: '',
                            overlayColor: 'rgba(0,0,0,0.4)',
                            textColor: '#ffffff',
                            padding: 80
                        }
                    },
                    {
                        type: 'couple',
                        name: 'Couple Profile',
                        icon: 'fas fa-heart',
                        description: 'Couple information and photos',
                        defaultSettings: {
                            bridePhoto: '',
                            brideName: 'Bride Name',
                            brideParents: 'Bride Parents',
                            groomPhoto: '',
                            groomName: 'Groom Name',
                            groomParents: 'Groom Parents',
                            layout: 'horizontal',
                            spacing: 30
                        }
                    }
                ]
            },
            {
                name: 'Basic Elements',
                elements: [
                    {
                        type: 'heading',
                        name: 'Heading',
                        icon: 'fas fa-heading',
                        description: 'Section title with multiple levels',
                        defaultSettings: {
                            text: 'New Heading',
                            level: 2,
                            alignment: 'center',
                            color: '#333333',
                            fontFamily: 'Playfair Display',
                            fontSize: 32,
                            margin: 20
                        }
                    },
                    {
                        type: 'text',
                        name: 'Text Block',
                        icon: 'fas fa-paragraph',
                        description: 'Regular text content',
                        defaultSettings: {
                            text: 'Enter your text here...',
                            alignment: 'left',
                            color: '#666666',
                            fontFamily: 'Poppins',
                            fontSize: 16,
                            lineHeight: 1.6
                        }
                    }
                ]
            }
        ],

        init() {
            // Initialize editor
        },

        addElement(elementType) {
            const newElement = {
                id: Date.now(),
                type: elementType.type,
                name: elementType.name,
                settings: { ...elementType.defaultSettings }
            };
            this.elements.push(newElement);
            this.selectElement(newElement);
        },

        selectElement(element) {
            this.selectedElement = element;
            this.settingsTab = 'content';
        },

        deselectElement() {
            this.selectedElement = null;
        },

        removeElement(element) {
            this.elements = this.elements.filter(e => e.id !== element.id);
            if (this.selectedElement?.id === element.id) {
                this.deselectElement();
            }
        },

        duplicateElement(element) {
            const clone = {
                ...element,
                id: Date.now(),
                settings: { ...element.settings }
            };
            const index = this.elements.findIndex(e => e.id === element.id);
            this.elements.splice(index + 1, 0, clone);
        },

        moveElement(index, direction) {
            const newIndex = index + direction;
            if (newIndex >= 0 && newIndex < this.elements.length) {
                const element = this.elements[index];
                this.elements.splice(index, 1);
                this.elements.splice(newIndex, 0, element);
            }
        },

        renderElement(element) {
            switch (element.type) {
                case 'cover': {
                    const bgImage = element.settings.backgroundImage || 'https://placehold.co/1200x800/cccccc/969696?text=Cover+Image';
                    return `
                        <div class="relative min-h-[80vh] flex items-center justify-center bg-cover bg-center" 
                             style="background-image: url('${bgImage}')">
                            <div class="absolute inset-0" style="background-color: ${element.settings.overlayColor}"></div>
                            <div class="relative text-center p-4" style="color: ${element.settings.textColor}">
                                <p class="text-sm tracking-widest uppercase mb-4">${element.settings.title}</p>
                                <h1 class="font-serif text-5xl md:text-7xl mb-6">
                                    ${element.settings.brideName}<br>&<br>${element.settings.groomName}
                                </h1>
                                <p class="text-lg">${element.settings.date}</p>
                            </div>
                        </div>`;
                }

                case 'couple': {
                    // FIX: Dynamic classes are constructed here and inline style is used for gap
                    const layoutClass = element.settings.layout === 'horizontal' ? 'md:flex-row' : 'md:flex-col';
                    const bridePhoto = element.settings.bridePhoto || 'https://placehold.co/192x192/e2e8f0/cccccc?text=Bride';
                    const groomPhoto = element.settings.groomPhoto || 'https://placehold.co/192x192/e2e8f0/cccccc?text=Groom';
                    
                    return `
                        <div class="flex flex-col ${layoutClass} justify-center items-center py-8" 
                             style="gap: ${element.settings.spacing}px;">
                            <div class="text-center">
                                <img src="${bridePhoto}" alt="Bride" class="w-48 h-48 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-md">
                                <h3 class="font-serif text-2xl mb-2">${element.settings.brideName}</h3>
                                <p class="text-gray-600">${element.settings.brideParents}</p>
                            </div>
                            <div class="text-6xl font-serif my-4 md:my-0 text-gray-300">&</div>
                            <div class="text-center">
                                <img src="${groomPhoto}" alt="Groom" class="w-48 h-48 object-cover rounded-full mx-auto mb-4 border-4 border-white shadow-md">
                                <h3 class="font-serif text-2xl mb-2">${element.settings.groomName}</h3>
                                <p class="text-gray-600">${element.settings.groomParents}</p>
                            </div>
                        </div>`;
                }

                case 'heading':
                    return `<h${element.settings.level} 
                                style="color: ${element.settings.color};
                                       font-family: '${element.settings.fontFamily}', serif;
                                       font-size: ${element.settings.fontSize}px;
                                       text-align: ${element.settings.alignment};
                                       margin: ${element.settings.margin}px 0;">
                                ${element.settings.text}
                            </h${element.settings.level}>`;

                case 'text':
                    return `<p style="color: ${element.settings.color};
                                     font-family: '${element.settings.fontFamily}', sans-serif;
                                     font-size: ${element.settings.fontSize}px;
                                     line-height: ${element.settings.lineHeight};
                                     text-align: ${element.settings.alignment};
                                     white-space: pre-wrap;">
                                ${element.settings.text}
                            </p>`;

                default:
                    return `<div class="p-4 border border-dashed border-gray-300 rounded">
                                Unknown element type: ${element.type}
                            </div>`;
            }
        }
    };
}
</script>
@endpush
