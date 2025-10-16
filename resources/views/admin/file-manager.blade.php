@extends('admin.layout')

@section('title', 'Gerenciador de Arquivos - Laser Link')
@section('page-title', 'Gerenciador de Arquivos')

@section('content')
<div class="space-y-6" x-data="fileManager()">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gerenciador de Arquivos</h2>
            <p class="text-gray-600" x-text="currentPath || 'Raiz do da pasta public'"></p>
        </div>
        <div class="flex space-x-3">
            <!-- Layout Switcher -->
            <div class="flex border border-gray-300 rounded-lg overflow-hidden">
                <button @click="viewMode = 'grid'; localStorage.setItem('fileManagerViewMode', 'grid')" 
                        :class="viewMode === 'grid' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-2 transition-colors">
                    <i class="bi bi-grid-3x3-gap"></i>
                </button>
                <button @click="viewMode = 'list'; localStorage.setItem('fileManagerViewMode', 'list')" 
                        :class="viewMode === 'list' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
                        class="px-3 py-2 transition-colors">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            
            <button @click="showCreateDirectory = true" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="bi bi-folder-plus mr-2"></i>Nova Pasta
            </button>
            <button @click="showUpload = true" 
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="bi bi-upload mr-2"></i>Upload
            </button>
            <button @click="loadItems()" 
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-clockwise mr-2"></i>Atualizar
            </button>
        </div>
    </div>

    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 text-sm text-gray-600">
        <button @click="navigateTo('')" class="hover:text-primary">
            <i class="bi bi-house"></i> Raiz
        </button>
        <template x-for="(part, index) in breadcrumb" :key="index">
            <div class="flex items-center space-x-2">
                <i class="bi bi-chevron-right text-xs"></i>
                <button @click="navigateTo(breadcrumb.slice(0, index + 1).join('/'))" 
                        class="hover:text-primary" 
                        x-text="part"></button>
            </div>
        </template>
    </div>

    <!-- Create Directory Modal -->
    <div x-show="showCreateDirectory" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
         @click="showCreateDirectory = false">
        <div @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-xl shadow-2xl p-6 w-96 mx-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Criar Nova Pasta</h3>
            <form @submit.prevent="createDirectory()">
                <input type="text" 
                       x-model="newDirectoryName"
                       placeholder="Nome da pasta"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                       required>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            @click="showCreateDirectory = false; newDirectoryName = ''"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            :disabled="creating"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors">
                        <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="creating"></i>
                        <span x-text="creating ? 'Criando...' : 'Criar'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Upload Modal -->
    <div x-show="showUpload" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
         @click="showUpload = false">
        <div @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-xl shadow-2xl p-6 w-96 mx-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Upload de Arquivo</h3>
            <form @submit.prevent="handleUpload()" enctype="multipart/form-data">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                     @drop.prevent="handleDrop($event)"
                     @dragover.prevent
                     @dragenter.prevent>
                    <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-4"></i>
                    <input type="file" 
                           @change="file = $event.target.files[0]; console.log('File selected:', $event.target.files[0])"
                           class="hidden"
                           id="fileManagerInput"
                           x-ref="fileInput">
                    <button type="button" 
                            @click.prevent="document.getElementById('fileManagerInput').click()"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors">
                        Selecionar Arquivo
                    </button>
                    <p class="text-sm text-gray-500 mt-2">Arraste um arquivo ou clique para selecionar (até 10MB)</p>
                    <p x-show="file" class="text-sm text-gray-700 mt-2 font-medium" x-text="file?.name"></p>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            @click="showUpload = false; file = null; document.getElementById('fileManagerInput').value = ''"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            :disabled="!file || uploading"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <i class="bi bi-upload mr-2" x-show="!uploading"></i>
                        <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="uploading"></i>
                        <span x-text="uploading ? 'Enviando...' : 'Enviar'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Files and Folders Grid -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div x-show="loading" class="text-center py-8">
                <i class="bi bi-arrow-clockwise animate-spin text-2xl text-gray-400"></i>
                <p class="text-gray-500 mt-2">Carregando...</p>
            </div>

            <div x-show="!loading && items.length === 0" class="text-center py-8">
                <i class="bi bi-folder text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">Pasta vazia</p>
            </div>

            <!-- Grid View -->
            <div x-show="!loading && items.length > 0 && viewMode === 'grid'" 
                 class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <template x-for="item in items" :key="item.path">
                    <div class="relative group cursor-pointer border border-gray-200 rounded-lg p-4 hover:bg-gray-50"
                         @click="item.type === 'directory' ? navigateTo(item.path) : selectItem(item)"
                         @contextmenu.prevent="showContextMenu($event, item)">
                        
                        <!-- Directory Icon -->
                        <div x-show="item.type === 'directory'" class="text-center">
                            <i class="bi bi-folder text-4xl text-blue-500 mb-2"></i>
                            <p class="text-sm font-medium truncate" x-text="item.name"></p>
                            <p class="text-xs text-gray-500">Pasta</p>
                        </div>

                        <!-- File Icon -->
                        <div x-show="item.type === 'file'" class="text-center">
                            <i class="bi bi-file-earmark text-4xl text-gray-500 mb-2" 
                               :class="getFileIcon(item.extension)"></i>
                            <p class="text-sm font-medium truncate" x-text="item.name"></p>
                            <p class="text-xs text-gray-500" x-text="formatFileSize(item.size)"></p>
                        </div>

                        <!-- Context Menu -->
                        <div x-show="contextMenu.show && contextMenu.item.path === item.path"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute top-0 right-0 bg-white border border-gray-200 rounded-lg shadow-xl z-20 min-w-32 overflow-hidden">
                            <button @click="renameItem(item)" 
                                    class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 flex items-center transition-colors">
                                <i class="bi bi-pencil mr-3 text-gray-600"></i>Renomear
                            </button>
                            <button @click="deleteItem(item)" 
                                    class="w-full px-4 py-3 text-left text-sm hover:bg-red-50 text-red-600 flex items-center transition-colors">
                                <i class="bi bi-trash mr-3"></i>Excluir
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- List View -->
            <div x-show="!loading && items.length > 0 && viewMode === 'list'" 
                 class="space-y-2">
                <!-- List Header -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 flex items-center space-x-4 text-xs font-medium text-gray-600">
                    <div class="w-8"></div> <!-- Icon space -->
                    <div class="flex-1">Nome</div>
                    <div class="w-20">Tipo</div>
                    <div class="w-20">Tamanho</div>
                    <div class="w-32">Modificado</div>
                </div>
                
                <template x-for="item in items" :key="item.path">
                    <div class="relative group cursor-pointer border border-gray-200 rounded-lg p-3 hover:bg-gray-50 flex items-center space-x-4"
                         @click="item.type === 'directory' ? navigateTo(item.path) : selectItem(item)"
                         @contextmenu.prevent="showContextMenu($event, item)">
                        
                        <!-- Icon -->
                        <div class="w-8 flex-shrink-0 flex items-center justify-center">
                            <template x-if="item.type === 'directory'">
                                <i class="bi bi-folder text-xl text-blue-500"></i>
                            </template>
                            <template x-if="item.type === 'file'">
                                <i class="text-xl" :class="getFileIcon(item.extension)"></i>
                            </template>
                        </div>

                        <!-- Name -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate" x-text="item.name"></p>
                        </div>

                        <!-- Type -->
                        <div class="w-20 text-xs text-gray-500">
                            <span x-show="item.type === 'directory'">Pasta</span>
                            <span x-show="item.type === 'file'" x-text="item.extension?.toUpperCase() || 'Arquivo'"></span>
                            <!-- Debug -->
                            <div x-show="item.type === 'file'" class="text-xs text-red-500" x-text="'Ext: ' + (item.extension || 'null')"></div>
                        </div>

                        <!-- Size -->
                        <div class="w-20 text-xs text-gray-500">
                            <span x-show="item.type === 'file'" x-text="formatFileSize(item.size)"></span>
                        </div>

                        <!-- Modified -->
                        <div class="w-32 text-xs text-gray-500">
                            <span x-text="formatDate(item.modified)"></span>
                        </div>

                        <!-- Context Menu -->
                        <div x-show="contextMenu.show && contextMenu.item.path === item.path"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute top-0 right-0 bg-white border border-gray-200 rounded-lg shadow-xl z-20 min-w-32 overflow-hidden">
                            <button @click="renameItem(item)" 
                                    class="w-full px-4 py-3 text-left text-sm hover:bg-gray-50 flex items-center transition-colors">
                                <i class="bi bi-pencil mr-3 text-gray-600"></i>Renomear
                            </button>
                            <button @click="deleteItem(item)" 
                                    class="w-full px-4 py-3 text-left text-sm hover:bg-red-50 text-red-600 flex items-center transition-colors">
                                <i class="bi bi-trash mr-3"></i>Excluir
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div x-show="showRename" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50"
         @click="showRename = false">
        <div @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="bg-white rounded-xl shadow-2xl p-6 w-96 mx-4">
            <h3 class="text-lg font-semibold mb-4 text-gray-900">Renomear</h3>
            <form @submit.prevent="handleRename()">
                <input type="text" 
                       x-model="renameValue"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors"
                       required>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            @click="showRename = false; renameValue = ''"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            :disabled="renaming"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-red-700 disabled:opacity-50 transition-colors">
                        <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="renaming"></i>
                        <span x-text="renaming ? 'Renomeando...' : 'Renomear'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fileManager() {
    return {
        items: [],
        currentPath: '',
        breadcrumb: [],
        loading: true,
        showCreateDirectory: false,
        showUpload: false,
        showRename: false,
        newDirectoryName: '',
        file: null,
        uploading: false,
        selectedItem: null,
        contextMenu: { show: false, item: null },
        renameValue: '',
        creating: false,
        renaming: false,
        viewMode: 'grid', // 'grid' or 'list'

        init() {
            // Carregar preferência de visualização
            const savedViewMode = localStorage.getItem('fileManagerViewMode');
            if (savedViewMode) {
                this.viewMode = savedViewMode;
            }
            
            this.loadItems();
            // Fechar context menu ao clicar fora
            document.addEventListener('click', () => {
                this.contextMenu.show = false;
            });
            
            // Fechar modais com ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.showCreateDirectory = false;
                    this.showUpload = false;
                    this.showRename = false;
                    this.contextMenu.show = false;
                }
            });
        },

        getCsrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]');
            return token ? token.getAttribute('content') : '';
        },

        handleDrop(event) {
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                this.file = files[0];
                console.log('File dropped:', this.file);
            }
        },

        async loadItems() {
            this.loading = true;
            try {
                // Remover 'public/' do início do caminho se existir
                const cleanPath = this.currentPath && this.currentPath.startsWith('public/') 
                    ? this.currentPath.replace('public/', '') 
                    : (this.currentPath || '');
                
                const url = `{{ route("admin.admin.file-manager.index") }}?directory=${cleanPath}`;
                const response = await fetch(url);
                const data = await response.json();
                this.items = data.items;
                this.currentPath = data.current_directory;
                this.breadcrumb = this.currentPath ? this.currentPath.split('/') : [];
            } catch (error) {
                console.error('Erro ao carregar itens:', error);
            } finally {
                this.loading = false;
            }
        },

        navigateTo(path) {
            // Limpar o caminho removendo 'public/' se existir
            this.currentPath = path && path.startsWith('public/') 
                ? path.replace('public/', '') 
                : (path || '');
            this.loadItems();
        },

        async createDirectory() {
            this.creating = true;
            try {
                const csrfToken = this.getCsrfToken();
                
                // Limpar o caminho para criar diretório
                const cleanPath = this.currentPath && this.currentPath.startsWith('public/') 
                    ? this.currentPath.replace('public/', '') 
                    : (this.currentPath || '');
                
                const response = await fetch('{{ route("admin.admin.file-manager.create-directory") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: this.newDirectoryName,
                        directory: cleanPath
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.loadItems();
                    this.showCreateDirectory = false;
                    this.newDirectoryName = '';
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

        async handleUpload() {
            if (!this.file) {
                alert('Por favor, selecione um arquivo primeiro.');
                return;
            }

            console.log('Iniciando upload:', this.file.name, 'Tamanho:', this.file.size);
            this.uploading = true;
            const formData = new FormData();
            formData.append('file', this.file);
            
            // Limpar o caminho para o upload
            const cleanPath = this.currentPath && this.currentPath.startsWith('public/') 
                ? this.currentPath.replace('public/', '') 
                : (this.currentPath || '');
            formData.append('directory', cleanPath);

            console.log('Upload para diretório:', cleanPath);

            try {
                const csrfToken = this.getCsrfToken();
                console.log('CSRF Token:', csrfToken ? 'Presente' : 'AUSENTE');
                
                const response = await fetch('{{ route("admin.admin.file-manager.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                console.log('Status da resposta:', response.status);
                
                const data = await response.json();
                console.log('Resposta do servidor:', data);
                
                if (data.success) {
                    console.log('Upload bem-sucedido!');
                    await this.loadItems();
                    
                    // Limpar o input e fechar modal
                    const inputElement = document.getElementById('fileManagerInput');
                    if (inputElement) {
                        inputElement.value = '';
                    }
                    this.file = null;
                    this.showUpload = false;
                    
                    // Mostrar mensagem de sucesso
                    const successMsg = document.createElement('div');
                    successMsg.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    successMsg.innerHTML = '<i class="bi bi-check-circle mr-2"></i>Arquivo enviado com sucesso!';
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    console.error('Falha no upload:', data);
                    alert('Erro no upload: ' + (data.message || 'Erro desconhecido'));
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                alert('Erro no upload: ' + error.message);
            } finally {
                this.uploading = false;
            }
        },

        selectItem(item) {
            this.selectedItem = item;
            // Aqui você pode adicionar lógica para selecionar arquivos
        },

        showContextMenu(event, item) {
            this.contextMenu = { show: true, item: item };
        },

        renameItem(item) {
            this.selectedItem = item;
            this.renameValue = item.name;
            this.showRename = true;
            this.contextMenu.show = false;
        },

        async handleRename() {
            this.renaming = true;
            try {
                const csrfToken = this.getCsrfToken();
                const response = await fetch('{{ route("admin.admin.file-manager.rename") }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        old_path: this.selectedItem.path,
                        new_name: this.renameValue
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.loadItems();
                    this.showRename = false;
                    this.renameValue = '';
                } else {
                    alert(data.message || 'Erro ao renomear');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao renomear');
            } finally {
                this.renaming = false;
            }
        },

        async deleteItem(item) {
            if (!confirm(`Tem certeza que deseja excluir "${item.name}"?`)) return;

            try {
                const csrfToken = this.getCsrfToken();
                const response = await fetch('{{ route("admin.admin.file-manager.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        path: item.path,
                        type: item.type
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.loadItems();
                } else {
                    alert(data.message || 'Erro ao excluir');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao excluir');
            }
            this.contextMenu.show = false;
        },

        getFileIcon(extension) {
            const icons = {
                'jpg': 'bi bi-file-earmark-image text-green-500',
                'jpeg': 'bi bi-file-earmark-image text-green-500',
                'png': 'bi bi-file-earmark-image text-green-500',
                'gif': 'bi bi-file-earmark-image text-green-500',
                'svg': 'bi bi-file-earmark-image text-green-500',
                'webp': 'bi bi-file-earmark-image text-green-500',
                'pdf': 'bi bi-file-earmark-pdf text-red-500',
                'doc': 'bi bi-file-earmark-word text-blue-500',
                'docx': 'bi bi-file-earmark-word text-blue-500',
                'xls': 'bi bi-file-earmark-excel text-green-500',
                'xlsx': 'bi bi-file-earmark-excel text-green-500',
                'txt': 'bi bi-file-earmark-text text-gray-500',
                'zip': 'bi bi-file-earmark-zip text-orange-500',
                'rar': 'bi bi-file-earmark-zip text-orange-500',
                'mp4': 'bi bi-file-earmark-play text-purple-500',
                'avi': 'bi bi-file-earmark-play text-purple-500',
                'mov': 'bi bi-file-earmark-play text-purple-500',
                'mp3': 'bi bi-file-earmark-music text-pink-500',
                'wav': 'bi bi-file-earmark-music text-pink-500',
                'css': 'bi bi-file-earmark-code text-blue-500',
                'js': 'bi bi-file-earmark-code text-yellow-500',
                'html': 'bi bi-file-earmark-code text-orange-500',
                'php': 'bi bi-file-earmark-code text-purple-500',
                'json': 'bi bi-file-earmark-code text-yellow-500'
            };
            const iconClass = icons[extension?.toLowerCase()] || 'bi bi-file-earmark text-gray-500';
            console.log('Extension:', extension, 'Icon class:', iconClass);
            return iconClass;
        },

        formatFileSize(bytes) {
            if (!bytes) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        formatDate(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp * 1000);
            return date.toLocaleDateString('pt-BR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    }
}
</script>
@endsection