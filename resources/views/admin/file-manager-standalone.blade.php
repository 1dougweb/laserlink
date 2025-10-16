@extends('admin.layout')

@section('title', 'Gerenciador de Arquivos - Laser Link')
@section('page-title', 'Gerenciador de Arquivos')

@section('content')
<div class="space-y-6" x-data="fileManagerPage()" x-init="init()">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Gerenciador de Arquivos</h2>
                <p class="text-gray-600" x-text="currentDirectory || 'Raiz da pasta public/images'"></p>
            </div>
            <div class="flex space-x-3">
                <!-- Botão Nova Pasta -->
                <button @click="openCreateFolderModal()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="bi bi-folder-plus mr-2"></i>Nova Pasta
                </button>
                
                <!-- Botão Upload -->
                <button @click="openUploadModal()" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="bi bi-upload mr-2"></i>Upload
                </button>
                
                <!-- Botão Atualizar -->
                <button @click="loadCurrentDirectory()" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="bi bi-arrow-clockwise mr-2"></i>Atualizar
                </button>
            </div>
        </div>
        
        <!-- Breadcrumb Navigation -->
        <div class="mb-4">
            <div id="breadcrumbContainer" class="flex items-center space-x-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                <button @click="navigateToFolder('')" 
                        class="hover:text-blue-600 flex items-center gap-1 transition-colors">
                    <i class="bi bi-house"></i>
                    <span>Raiz</span>
                </button>
            </div>
        </div>
        
        <!-- Busca -->
        <div class="mb-4">
            <div class="relative max-w-md">
                <input type="text" 
                       x-model="searchTerm"
                       @input="filterItems()"
                       placeholder="Buscar arquivos e pastas..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div x-show="loading" class="bg-white rounded-lg shadow p-12">
        <div class="text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                <i class="bi bi-folder2-open text-3xl text-blue-600 animate-pulse"></i>
            </div>
            <p class="text-gray-600 font-medium">Carregando arquivos...</p>
        </div>
    </div>

    <!-- Conteúdo -->
    <div x-show="!loading" class="space-y-6">
        <!-- Pastas -->
        <div x-show="folders.length > 0" class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="bi bi-folder text-blue-500 mr-2"></i>
                Pastas (<span x-text="folders.length"></span>)
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <template x-for="folder in folders" :key="folder.path">
                    <div @click="navigateToFolder(folder.path)" 
                         class="group relative cursor-pointer">
                        <div class="aspect-square bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg overflow-hidden border-2 border-transparent group-hover:border-blue-500 transition-all flex items-center justify-center">
                            <i class="bi bi-folder text-5xl text-blue-500 group-hover:scale-110 transition-transform"></i>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-sm font-medium text-gray-900 truncate" x-text="folder.name"></p>
                        </div>
                        <!-- Ações rápidas -->
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click.stop="deleteItem(folder)" 
                                    class="bg-red-500 text-white w-7 h-7 rounded-full hover:bg-red-600 flex items-center justify-center"
                                    title="Excluir pasta">
                                <i class="bi bi-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Arquivos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="bi bi-file-earmark-image text-green-500 mr-2"></i>
                Arquivos (<span x-text="files.length"></span>)
            </h3>
            
            <div x-show="files.length === 0" class="text-center py-12">
                <i class="bi bi-inbox text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 font-medium">Nenhum arquivo encontrado</p>
                <p class="text-sm text-gray-400 mt-2">Faça upload de arquivos ou navegue para outra pasta</p>
            </div>
            
            <div x-show="files.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <template x-for="file in files" :key="file.path">
                    <div class="group relative">
                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-transparent group-hover:border-green-500 transition-all">
                            <img :src="file.url" 
                                 :alt="file.name"
                                 class="w-full h-full object-cover"
                                 onerror="this.src='{{ asset('images/no-image.svg') }}'">
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-sm font-medium text-gray-900 truncate" :title="file.name" x-text="file.name"></p>
                            <p class="text-xs text-gray-500" x-text="formatFileSize(file.size)"></p>
                        </div>
                        <!-- Ações rápidas -->
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                            <button @click.stop="copyUrl(file)" 
                                    class="bg-blue-500 text-white w-7 h-7 rounded-full hover:bg-blue-600 flex items-center justify-center"
                                    title="Copiar caminho da imagem">
                                <i class="bi bi-clipboard text-xs"></i>
                            </button>
                            <button @click.stop="deleteItem(file)" 
                                    class="bg-red-500 text-white w-7 h-7 rounded-full hover:bg-red-600 flex items-center justify-center"
                                    title="Excluir arquivo">
                                <i class="bi bi-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Modal Upload -->
    <div x-show="showUploadModal" 
         x-transition
         class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center"
         @click.self="closeUploadModal()">
        <div class="mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">📤 Upload de Arquivo</h3>
                    <button @click="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>
                
                <div x-show="!selectedFile">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors cursor-pointer"
                         @click="$refs.fileInput.click()"
                         @drop.prevent="handleDrop($event)"
                         @dragover.prevent>
                        <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Clique ou arraste um arquivo aqui</p>
                        <p class="text-sm text-gray-500">Até 10MB</p>
                        <input type="file" 
                               x-ref="fileInput"
                               @change="handleFileSelect($event)"
                               class="hidden">
                    </div>
                </div>
                
                <div x-show="selectedFile" class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-file-earmark text-2xl text-gray-600"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-900" x-text="selectedFile?.name"></p>
                                <p class="text-xs text-gray-500" x-text="selectedFile ? formatFileSize(selectedFile.size) : ''"></p>
                            </div>
                        </div>
                        <button @click="clearFileSelection()" class="text-red-500 hover:text-red-700">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="mt-4 flex gap-3">
                    <button @click="closeUploadModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button @click="uploadFile()" 
                            :disabled="!selectedFile || uploading"
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="uploading ? 'Enviando...' : 'Fazer Upload'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Create Folder -->
    <div x-show="showCreateFolderModal" 
         x-transition
         class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center"
         @click.self="closeCreateFolderModal()">
        <div class="mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Nova Pasta</h3>
                    <button @click="closeCreateFolderModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Pasta</label>
                    <input type="text" 
                           x-model="folderName"
                           @keydown.enter="createFolder()"
                           placeholder="Digite o nome da pasta"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex gap-3">
                    <button @click="closeCreateFolderModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                        Cancelar
                    </button>
                    <button @click="createFolder()" 
                            :disabled="!folderName || creating"
                            class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-text="creating ? 'Criando...' : 'Criar Pasta'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Confirmation -->
    <div x-show="showDeleteModal" 
         x-transition
         class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center"
         @click.self="closeDeleteModal()">
        <div class="mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="bi bi-exclamation-triangle text-red-500 text-2xl mr-2"></i>
                        Confirmar Exclusão
                    </h3>
                    <button @click="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>
                
                <div class="mb-6">
                    <p class="text-gray-700 mb-2">Tem certeza que deseja excluir:</p>
                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                        <div class="flex items-center gap-2">
                            <i :class="itemToDelete?.type === 'directory' ? 'bi bi-folder text-blue-500' : 'bi bi-file-image text-green-500'" 
                               class="text-xl"></i>
                            <span class="font-medium text-gray-900" x-text="itemToDelete?.name"></span>
                        </div>
                    </div>
                    <p class="text-sm text-red-600 mt-3" x-show="itemToDelete?.type === 'directory'">
                        <i class="bi bi-exclamation-circle mr-1"></i>
                        Atenção: Todos os arquivos dentro desta pasta também serão excluídos!
                    </p>
                </div>
                
                <div class="flex gap-3">
                    <button @click="closeDeleteModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                        <i class="bi bi-x mr-2"></i>Cancelar
                    </button>
                    <button @click="confirmDelete()" 
                            :disabled="deleting"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="bi bi-trash mr-2"></i>
                        <span x-text="deleting ? 'Excluindo...' : 'Excluir'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fileManagerPage() {
    return {
        currentDirectory: '',
        allItems: [],
        folders: [],
        files: [],
        loading: true,
        showUploadModal: false,
        showCreateFolderModal: false,
        showDeleteModal: false,
        selectedFile: null,
        uploading: false,
        creating: false,
        deleting: false,
        folderName: '',
        searchTerm: '',
        itemToDelete: null,
        
        init() {
            this.loadCurrentDirectory();
        },
        
        async loadCurrentDirectory() {
            this.navigateToFolder(this.currentDirectory);
        },
        
        async navigateToFolder(directory) {
            this.loading = true;
            this.currentDirectory = directory || '';
            
            try {
                const url = directory 
                    ? `{{ route("admin.admin.file-manager.index") }}?directory=${encodeURIComponent(directory)}`
                    : '{{ route("admin.admin.file-manager.index") }}';
                
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success && data.items) {
                    this.allItems = data.items;
                    this.updateBreadcrumb(data.breadcrumb || []);
                    this.filterItems();
                } else {
                    throw new Error(data.message || 'Erro ao carregar arquivos');
                }
            } catch (error) {
                console.error('Erro ao carregar arquivos:', error);
                alert('Erro ao carregar arquivos: ' + error.message);
            } finally {
                this.loading = false;
            }
        },
        
        updateBreadcrumb(breadcrumbItems) {
            const breadcrumb = document.getElementById('breadcrumbContainer');
            if (!breadcrumb) return;
            
            let html = `
                <button @click="navigateToFolder('')" 
                        class="hover:text-blue-600 flex items-center gap-1 transition-colors">
                    <i class="bi bi-house"></i>
                    <span>Raiz</span>
                </button>
            `;
            
            if (breadcrumbItems.length > 0) {
                breadcrumbItems.forEach((item, index) => {
                    const isLast = index === breadcrumbItems.length - 1;
                    html += `
                        <i class="bi bi-chevron-right text-gray-400"></i>
                        <button @click="navigateToFolder('${item.path}')" 
                                class="${isLast ? 'font-semibold text-gray-900' : 'hover:text-blue-600'} transition-colors">
                            ${item.name}
                        </button>
                    `;
                });
            }
            
            breadcrumb.innerHTML = html;
        },
        
        filterItems() {
            const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg'];
            
            let items = this.allItems;
            
            // Aplicar filtro de busca
            if (this.searchTerm) {
                items = items.filter(item => 
                    item.name.toLowerCase().includes(this.searchTerm.toLowerCase())
                );
            }
            
            // Separar pastas e arquivos
            this.folders = items.filter(item => item.type === 'directory');
            this.files = items.filter(item => 
                item.type === 'file' && 
                imageExtensions.some(ext => item.name.toLowerCase().endsWith(ext))
            );
        },
        
        openUploadModal() {
            this.showUploadModal = true;
        },
        
        closeUploadModal() {
            this.showUploadModal = false;
            this.clearFileSelection();
        },
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.selectedFile = file;
            }
        },
        
        handleDrop(event) {
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                this.selectedFile = files[0];
            }
        },
        
        clearFileSelection() {
            this.selectedFile = null;
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },
        
        async uploadFile() {
            if (!this.selectedFile) return;
            
            this.uploading = true;
            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('directory', this.currentDirectory);
            
            try {
                const response = await fetch('{{ route("admin.admin.file-manager.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Upload realizado com sucesso!');
                    this.closeUploadModal();
                    this.loadCurrentDirectory();
                } else {
                    alert(data.message || 'Erro ao fazer upload');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao fazer upload');
            } finally {
                this.uploading = false;
            }
        },
        
        openCreateFolderModal() {
            this.showCreateFolderModal = true;
            setTimeout(() => {
                const input = this.$el.querySelector('input[x-model="folderName"]');
                if (input) input.focus();
            }, 100);
        },
        
        closeCreateFolderModal() {
            this.showCreateFolderModal = false;
            this.folderName = '';
        },
        
        async createFolder() {
            if (!this.folderName.trim()) {
                alert('Digite um nome para a pasta');
                return;
            }
            
            this.creating = true;
            
            try {
                const response = await fetch('{{ route("admin.admin.file-manager.create-directory") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: this.folderName,
                        directory: this.currentDirectory
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Pasta criada com sucesso!');
                    this.closeCreateFolderModal();
                    this.loadCurrentDirectory();
                } else {
                    alert(data.message || 'Erro ao criar pasta');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao criar pasta');
            } finally {
                this.creating = false;
            }
        },
        
        deleteItem(item) {
            this.itemToDelete = item;
            this.showDeleteModal = true;
        },
        
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.itemToDelete = null;
        },
        
        async confirmDelete() {
            if (!this.itemToDelete) return;
            
            this.deleting = true;
            
            try {
                const response = await fetch('{{ route("admin.admin.file-manager.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        path: this.itemToDelete.path,
                        type: this.itemToDelete.type
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('✅ Item excluído!', `${this.itemToDelete.name} foi removido com sucesso`, 'success');
                    this.closeDeleteModal();
                    this.loadCurrentDirectory();
                } else {
                    this.showToast('❌ Erro ao excluir', data.message || 'Não foi possível excluir o item', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                this.showToast('❌ Erro ao excluir', 'Ocorreu um erro ao tentar excluir o item', 'error');
            } finally {
                this.deleting = false;
            }
        },
        
        async copyUrl(file) {
            const path = file.path;
            
            try {
                // Tentar usar a API moderna de clipboard
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(path);
                    this.showToast('✅ Caminho copiado!', `${path}`, 'success');
                } else {
                    // Fallback para navegadores antigos ou HTTP
                    const textArea = document.createElement('textarea');
                    textArea.value = path;
                    textArea.style.position = 'fixed';
                    textArea.style.left = '-999999px';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    
                    try {
                        const successful = document.execCommand('copy');
                        document.body.removeChild(textArea);
                        
                        if (successful) {
                            this.showToast('✅ Caminho copiado!', `${path}`, 'success');
                        } else {
                            throw new Error('execCommand falhou');
                        }
                    } catch (err) {
                        document.body.removeChild(textArea);
                        // Última opção: mostrar o caminho para copiar manualmente
                        this.showCopyModal(path);
                    }
                }
            } catch (err) {
                console.error('Erro ao copiar:', err);
                // Mostrar modal com o caminho para copiar manualmente
                this.showCopyModal(path);
            }
        },
        
        showCopyModal(path) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full mx-4">
                    <h3 class="text-lg font-semibold mb-4">Copiar Caminho</h3>
                    <p class="text-sm text-gray-600 mb-3">Copie o caminho abaixo:</p>
                    <div class="bg-gray-50 p-3 rounded border border-gray-200 mb-4">
                        <input type="text" 
                               value="${path}" 
                               readonly 
                               onclick="this.select()"
                               class="w-full bg-transparent text-sm text-gray-900 outline-none">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button onclick="this.closest('.fixed').remove()" 
                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg">
                            Fechar
                        </button>
                        <button onclick="this.previousElementSibling.previousElementSibling.querySelector('input').select(); document.execCommand('copy'); this.closest('.fixed').remove();" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            <i class="bi bi-clipboard mr-2"></i>Copiar
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            // Auto-selecionar o texto
            setTimeout(() => {
                const input = modal.querySelector('input');
                if (input) input.select();
            }, 100);
            
            // Fechar ao clicar fora
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        },
        
        showToast(title, message, type = 'success') {
            // Criar elemento de toast
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 z-[60] max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 transform transition-all duration-300 translate-x-full`;
            
            const bgColor = type === 'success' ? 'bg-green-50' : type === 'error' ? 'bg-red-50' : 'bg-blue-50';
            const textColor = type === 'success' ? 'text-green-800' : type === 'error' ? 'text-red-800' : 'text-blue-800';
            const iconColor = type === 'success' ? 'text-green-400' : type === 'error' ? 'text-red-400' : 'text-blue-400';
            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle';
            
            toast.innerHTML = `
                <div class="${bgColor} p-4 rounded-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-${icon} ${iconColor} text-xl"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium ${textColor}">${title}</p>
                            <p class="mt-1 text-xs text-gray-600 break-all">${message}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button onclick="this.closest('.fixed').remove()" 
                                    class="inline-flex ${textColor} hover:text-gray-500">
                                <i class="bi bi-x text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animar entrada
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 10);
            
            // Auto-remover após 4 segundos
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        },
        
        formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    };
}
</script>

@endsection

