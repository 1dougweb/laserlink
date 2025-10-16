<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['modalId' => 'fileManagerModal', 'title' => 'Selecionar Imagem', 'onSelectCallback' => 'selectImage']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['modalId' => 'fileManagerModal', 'title' => 'Selecionar Imagem', 'onSelectCallback' => 'selectImage']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!-- Modal do File Manager -->
<div id="<?php echo e($modalId); ?>" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 mx-auto p-0 border w-11/12 max-w-6xl shadow-lg rounded-2xl bg-white">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-white rounded-t-2xl">
            <div>
                <h3 class="text-xl font-semibold text-gray-900"><?php echo e($title); ?></h3>
                <p class="text-sm text-gray-600">Navegue, faça upload ou crie pastas</p>
            </div>
            <button onclick="closeFileManager<?php echo e($modalId); ?>()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-x text-gray-600 text-lg"></i>
            </button>
        </div>
        
        <!-- File Manager Content -->
        <div class="flex-1 p-6 overflow-hidden">
            <!-- Breadcrumb Navigation -->
            <div class="mb-4">
                <div id="<?php echo e($modalId); ?>BreadcrumbContainer" class="flex items-center space-x-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                    <button onclick="navigateToFolder<?php echo e($modalId); ?>('')" 
                            class="hover:text-blue-600 flex items-center gap-1 transition-colors">
                        <i class="bi bi-house"></i>
                        <span>Raiz</span>
                    </button>
                </div>
            </div>
            
            <!-- Toolbar -->
            <div class="mb-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
                <!-- Busca -->
                <div class="flex-1 max-w-md">
                    <div class="relative">
                        <input type="text" 
                               id="<?php echo e($modalId); ?>SearchInput"
                               placeholder="Buscar imagens..."
                               onkeyup="filterImages<?php echo e($modalId); ?>(this.value)"
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Ações -->
                <div class="flex gap-2">
                    <!-- Botão Nova Pasta -->
                    <button onclick="openCreateFolderModal<?php echo e($modalId); ?>()" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <i class="bi bi-folder-plus"></i>
                        <span class="hidden sm:inline">Nova Pasta</span>
                    </button>
                    
                    <!-- Botão Upload -->
                    <button onclick="openUploadModal<?php echo e($modalId); ?>()" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <i class="bi bi-upload"></i>
                        <span class="hidden sm:inline">Upload</span>
                    </button>
                    
                    <!-- Botão Atualizar -->
                    <button onclick="loadCurrentDirectory<?php echo e($modalId); ?>()" 
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading -->
            <div id="<?php echo e($modalId); ?>Loading" class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="bi bi-image text-2xl text-blue-600"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Carregando...</p>
                </div>
            </div>
            
            <!-- File Grid -->
            <div id="<?php echo e($modalId); ?>Grid" class="hidden overflow-y-auto max-h-96">
                <!-- Pastas -->
                <div id="<?php echo e($modalId); ?>FoldersSection" class="mb-6 hidden">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-folder text-blue-500 mr-2"></i>
                        Pastas
                    </h4>
                    <div id="<?php echo e($modalId); ?>FoldersGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                </div>
                
                <!-- Arquivos -->
                <div id="<?php echo e($modalId); ?>FilesSection" class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-file-image text-green-500 mr-2"></i>
                        Imagens
                    </h4>
                    <div id="<?php echo e($modalId); ?>FilesGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                </div>
            </div>
            
            <!-- Barra de Seleção Múltipla -->
            <div id="<?php echo e($modalId); ?>MultiSelectBar" class="hidden bg-blue-50 border-t border-blue-200 p-4 rounded-b-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <i class="bi bi-check-circle text-blue-600"></i>
                            <span class="text-sm font-medium text-blue-800">
                                <span id="<?php echo e($modalId); ?>SelectedCount">0</span> arquivo(s) selecionado(s)
                            </span>
                        </div>
                        <div id="<?php echo e($modalId); ?>SelectedFilesList" class="text-xs text-blue-600 max-w-md truncate">
                            <!-- Lista de arquivos selecionados -->
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="clearAllSelections<?php echo e($modalId); ?>()" 
                                class="px-3 py-1 text-xs bg-red-100 hover:bg-red-200 text-red-700 rounded transition-colors">
                            Limpar
                        </button>
                        <button id="<?php echo e($modalId); ?>ConfirmBtn" onclick="confirmMultipleSelection<?php echo e($modalId); ?>()" 
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm">
                            Selecionar Imagens
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
            <div class="text-sm text-gray-600">
                <i class="bi bi-info-circle mr-1"></i>
                Clique para selecionar • Duplo clique para confirmar • Segure <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Ctrl</kbd> para seleção múltipla
            </div>
            <button onclick="closeFileManager<?php echo e($modalId); ?>()" 
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="<?php echo e($modalId); ?>UploadModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-[60] flex items-center justify-center">
    <div class="mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">📤 Upload de Imagem</h3>
                <button onclick="closeUploadModal<?php echo e($modalId); ?>()" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <div id="<?php echo e($modalId); ?>UploadContent">
                <div id="<?php echo e($modalId); ?>UploadArea" 
                     class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer"
                     onclick="document.getElementById('<?php echo e($modalId); ?>FileInput').click()">
                    <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 mb-2">Clique ou arraste uma imagem aqui</p>
                    <p class="text-sm text-gray-500">PNG, JPG, GIF até 10MB</p>
                    <input type="file" 
                           id="<?php echo e($modalId); ?>FileInput" 
                           accept="image/*" 
                           class="hidden"
                           onchange="handleFileSelect<?php echo e($modalId); ?>(event)">
                </div>
                
                <div id="<?php echo e($modalId); ?>UploadPreview" class="hidden mt-4">
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                        <div class="flex items-center gap-3">
                            <img id="<?php echo e($modalId); ?>PreviewImage" src="" alt="Preview" class="w-12 h-12 object-cover rounded">
                            <div>
                                <p id="<?php echo e($modalId); ?>FileName" class="text-sm font-medium text-gray-900"></p>
                                <p id="<?php echo e($modalId); ?>FileSize" class="text-xs text-gray-500"></p>
                            </div>
                        </div>
                        <button onclick="clearFileSelection<?php echo e($modalId); ?>()" class="text-red-500 hover:text-red-700">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 flex gap-3">
                <button onclick="closeUploadModal<?php echo e($modalId); ?>()" 
                        class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button onclick="uploadFile<?php echo e($modalId); ?>()" 
                        id="<?php echo e($modalId); ?>UploadBtn"
                        class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    <span id="<?php echo e($modalId); ?>UploadBtnText">Fazer Upload</span>
                </button>
            </div>
            
            <div id="<?php echo e($modalId); ?>UploadProgress" class="hidden mt-4">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div id="<?php echo e($modalId); ?>ProgressBar" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Folder Modal -->
<div id="<?php echo e($modalId); ?>CreateFolderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-[60] flex items-center justify-center">
    <div class="mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Nova Pasta</h3>
                <button onclick="closeCreateFolderModal<?php echo e($modalId); ?>()" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Pasta</label>
                <input type="text" 
                       id="<?php echo e($modalId); ?>FolderNameInput"
                       placeholder="Digite o nome da pasta"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       onkeypress="if(event.key === 'Enter') createFolder<?php echo e($modalId); ?>()">
            </div>
            
            <div class="flex gap-3">
                <button onclick="closeCreateFolderModal<?php echo e($modalId); ?>()" 
                        class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                    Cancelar
                </button>
                <button onclick="createFolder<?php echo e($modalId); ?>()" 
                        class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Criar Pasta
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variáveis globais para <?php echo e($modalId); ?>

let <?php echo e($modalId); ?>CurrentDirectory = '';
let <?php echo e($modalId); ?>AllItems = [];
let <?php echo e($modalId); ?>SelectedFile = null;
let <?php echo e($modalId); ?>SelectedFiles = [];
let <?php echo e($modalId); ?>MultiSelectMode = false;

// Abrir file manager (função será definida mais abaixo com funcionalidade de seleção múltipla)

// Fechar file manager
function closeFileManager<?php echo e($modalId); ?>() {
    document.getElementById('<?php echo e($modalId); ?>').classList.add('hidden');
}

// Carregar diretório atual
async function loadCurrentDirectory<?php echo e($modalId); ?>() {
    navigateToFolder<?php echo e($modalId); ?>(<?php echo e($modalId); ?>CurrentDirectory);
}

// Navegar para pasta
async function navigateToFolder<?php echo e($modalId); ?>(directory) {
    const loading = document.getElementById('<?php echo e($modalId); ?>Loading');
    const grid = document.getElementById('<?php echo e($modalId); ?>Grid');
    
    // Mostrar loading
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    
    // Atualizar diretório atual
    <?php echo e($modalId); ?>CurrentDirectory = directory || '';
    
    try {
        const url = directory 
            ? `<?php echo e(route("admin.admin.file-manager.index")); ?>?directory=${encodeURIComponent(directory)}`
            : '<?php echo e(route("admin.admin.file-manager.index")); ?>';
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success && data.items) {
            <?php echo e($modalId); ?>AllItems = data.items;
            updateBreadcrumb<?php echo e($modalId); ?>(data.breadcrumb || []);
            renderItems<?php echo e($modalId); ?>(data.items);
            
            // Limpar busca
            const searchInput = document.getElementById('<?php echo e($modalId); ?>SearchInput');
            if (searchInput) {
                searchInput.value = '';
            }
        } else {
            throw new Error(data.message || 'Erro ao carregar arquivos');
        }
    } catch (error) {
        console.error('Erro ao carregar arquivos:', error);
        
        // Mostrar erro no grid
        grid.classList.remove('hidden');
        const filesGrid = document.getElementById('<?php echo e($modalId); ?>FilesGrid');
        if (filesGrid) {
            filesGrid.innerHTML = `
                <div class="col-span-full text-center py-8">
                    <i class="bi bi-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                    <p class="text-red-600 font-medium">Erro ao carregar arquivos</p>
                    <p class="text-sm text-gray-500 mt-2">${error.message}</p>
                    <button onclick="navigateToFolder<?php echo e($modalId); ?>('${directory}')" 
                            class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        <i class="bi bi-arrow-clockwise mr-2"></i>Tentar Novamente
                    </button>
                </div>
            `;
        }
    } finally {
        loading.classList.add('hidden');
        grid.classList.remove('hidden');
    }
}

// Atualizar breadcrumb
function updateBreadcrumb<?php echo e($modalId); ?>(breadcrumbItems) {
    const breadcrumb = document.getElementById('<?php echo e($modalId); ?>BreadcrumbContainer');
    if (!breadcrumb) return;
    
    let html = `
        <button onclick="navigateToFolder<?php echo e($modalId); ?>('')" 
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
                <button onclick="navigateToFolder<?php echo e($modalId); ?>('${item.path}')" 
                        class="${isLast ? 'font-semibold text-gray-900' : 'hover:text-blue-600'} transition-colors">
                    ${item.name}
                </button>
            `;
        });
    }
    
    breadcrumb.innerHTML = html;
}

// Renderizar itens
function renderItems<?php echo e($modalId); ?>(items) {
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp', '.svg'];
    const folders = items.filter(item => item.type === 'directory');
    const files = items.filter(item => 
        item.type === 'file' && 
        imageExtensions.some(ext => item.name.toLowerCase().endsWith(ext))
    );
    
    // Renderizar pastas
    const foldersSection = document.getElementById('<?php echo e($modalId); ?>FoldersSection');
    const foldersGrid = document.getElementById('<?php echo e($modalId); ?>FoldersGrid');
    
    if (foldersGrid && folders.length > 0) {
        foldersSection.classList.remove('hidden');
        foldersGrid.innerHTML = folders.map(folder => `
            <div onclick="navigateToFolder<?php echo e($modalId); ?>('${folder.path}')" 
                 class="group relative cursor-pointer">
                <div class="aspect-square bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg overflow-hidden border-2 border-transparent group-hover:border-blue-500 transition-all flex items-center justify-center">
                    <i class="bi bi-folder text-5xl text-blue-500 group-hover:scale-110 transition-transform"></i>
                </div>
                <div class="mt-2 text-center">
                    <p class="text-sm font-medium text-gray-900 truncate">${folder.name}</p>
                </div>
            </div>
        `).join('');
    } else if (foldersSection) {
        foldersSection.classList.add('hidden');
    }
    
    // Renderizar arquivos
    const filesSection = document.getElementById('<?php echo e($modalId); ?>FilesSection');
    const filesGrid = document.getElementById('<?php echo e($modalId); ?>FilesGrid');
    
    if (filesGrid) {
        if (files.length > 0) {
            filesSection.classList.remove('hidden');
            filesGrid.innerHTML = files.map(item => {
                const isSelected = <?php echo e($modalId); ?>SelectedFiles.some(selected => selected.path === item.path);
                const selectedClass = isSelected ? 'border-blue-500 bg-blue-50' : 'border-transparent';
                const checkIcon = isSelected ? 'bi-check-circle-fill text-blue-600' : 'bi-check-circle text-white opacity-0 group-hover:opacity-100';
                
                return `
                    <div onclick="handleFileClick<?php echo e($modalId); ?>('${item.path}', event)" 
                         ondblclick="handleFileDoubleClick<?php echo e($modalId); ?>('${item.path}')"
                         class="group relative cursor-pointer file-item" 
                         data-path="${item.path}">
                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 ${selectedClass} group-hover:border-green-500 transition-all relative">
                            <img src="${item.url}" 
                                 alt="${item.name}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform"
                                 onerror="this.src='<?php echo e(asset('images/no-image.svg')); ?>'">
                            <!-- Indicador de seleção -->
                            <div class="absolute top-2 right-2">
                                <i class="bi ${checkIcon} text-lg transition-all ${isSelected ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'}"></i>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all rounded-lg flex items-center justify-center">
                            <i class="bi bi-check-circle text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        <div class="mt-2 text-center">
                            <p class="text-sm font-medium text-gray-900 truncate" title="${item.name}">${item.name}</p>
                        </div>
                    </div>
                `;
            }).join('');
        } else {
            filesSection.classList.remove('hidden');
            filesGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="bi bi-image text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-medium">Nenhuma imagem encontrada</p>
                    <p class="text-sm text-gray-400 mt-2">Faça upload de imagens ou navegue para outra pasta</p>
                </div>
            `;
        }
    }
    
    // Se não há pastas nem imagens, mostrar mensagem vazia
    if (folders.length === 0 && files.length === 0) {
        if (filesSection) filesSection.classList.remove('hidden');
        if (filesGrid) {
            filesGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="bi bi-folder-x text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-medium">Pasta vazia</p>
                    <p class="text-sm text-gray-400 mt-2">Crie uma nova pasta ou faça upload de imagens</p>
                </div>
            `;
        }
    }
}

// Filtrar imagens
function filterImages<?php echo e($modalId); ?>(searchTerm) {
    if (!searchTerm) {
        renderItems<?php echo e($modalId); ?>(<?php echo e($modalId); ?>AllItems);
        return;
    }
    
    const filtered = <?php echo e($modalId); ?>AllItems.filter(item => 
        item.name.toLowerCase().includes(searchTerm.toLowerCase())
    );
    renderItems<?php echo e($modalId); ?>(filtered);
}

// Upload Modal
function openUploadModal<?php echo e($modalId); ?>() {
    document.getElementById('<?php echo e($modalId); ?>UploadModal').classList.remove('hidden');
}

function closeUploadModal<?php echo e($modalId); ?>() {
    document.getElementById('<?php echo e($modalId); ?>UploadModal').classList.add('hidden');
    clearFileSelection<?php echo e($modalId); ?>();
}

function handleFileSelect<?php echo e($modalId); ?>(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    <?php echo e($modalId); ?>SelectedFile = file;
    
    // Mostrar preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('<?php echo e($modalId); ?>PreviewImage').src = e.target.result;
        document.getElementById('<?php echo e($modalId); ?>FileName').textContent = file.name;
        document.getElementById('<?php echo e($modalId); ?>FileSize').textContent = (file.size / 1024).toFixed(2) + ' KB';
        document.getElementById('<?php echo e($modalId); ?>UploadArea').classList.add('hidden');
        document.getElementById('<?php echo e($modalId); ?>UploadPreview').classList.remove('hidden');
        document.getElementById('<?php echo e($modalId); ?>UploadBtn').disabled = false;
    };
    reader.readAsDataURL(file);
}

function clearFileSelection<?php echo e($modalId); ?>() {
    <?php echo e($modalId); ?>SelectedFile = null;
    document.getElementById('<?php echo e($modalId); ?>FileInput').value = '';
    document.getElementById('<?php echo e($modalId); ?>UploadArea').classList.remove('hidden');
    document.getElementById('<?php echo e($modalId); ?>UploadPreview').classList.add('hidden');
    document.getElementById('<?php echo e($modalId); ?>UploadBtn').disabled = true;
}

async function uploadFile<?php echo e($modalId); ?>() {
    if (!<?php echo e($modalId); ?>SelectedFile) return;
    
    const formData = new FormData();
    formData.append('file', <?php echo e($modalId); ?>SelectedFile);
    formData.append('directory', <?php echo e($modalId); ?>CurrentDirectory);
    
    const uploadBtn = document.getElementById('<?php echo e($modalId); ?>UploadBtn');
    const uploadBtnText = document.getElementById('<?php echo e($modalId); ?>UploadBtnText');
    const progressDiv = document.getElementById('<?php echo e($modalId); ?>UploadProgress');
    const progressBar = document.getElementById('<?php echo e($modalId); ?>ProgressBar');
    
    uploadBtn.disabled = true;
    uploadBtnText.textContent = 'Enviando...';
    progressDiv.classList.remove('hidden');
    
    try {
        const response = await fetch('<?php echo e(route("admin.admin.file-manager.upload")); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Upload realizado com sucesso!', 'success');
            closeUploadModal<?php echo e($modalId); ?>();
            loadCurrentDirectory<?php echo e($modalId); ?>();
        } else {
            showNotification(data.message || 'Erro ao fazer upload', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showNotification('Erro ao fazer upload', 'error');
    } finally {
        uploadBtn.disabled = false;
        uploadBtnText.textContent = 'Fazer Upload';
        progressDiv.classList.add('hidden');
        progressBar.style.width = '0%';
    }
}

// Create Folder Modal
function openCreateFolderModal<?php echo e($modalId); ?>() {
    document.getElementById('<?php echo e($modalId); ?>CreateFolderModal').classList.remove('hidden');
    document.getElementById('<?php echo e($modalId); ?>FolderNameInput').focus();
}

function closeCreateFolderModal<?php echo e($modalId); ?>() {
    document.getElementById('<?php echo e($modalId); ?>CreateFolderModal').classList.add('hidden');
    document.getElementById('<?php echo e($modalId); ?>FolderNameInput').value = '';
}

async function createFolder<?php echo e($modalId); ?>() {
    const folderName = document.getElementById('<?php echo e($modalId); ?>FolderNameInput').value.trim();
    
    if (!folderName) {
        showNotification('Digite um nome para a pasta', 'warning');
        return;
    }
    
    try {
        const response = await fetch('<?php echo e(route("admin.admin.file-manager.create-directory")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                name: folderName,
                directory: <?php echo e($modalId); ?>CurrentDirectory
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Pasta criada com sucesso!', 'success');
            closeCreateFolderModal<?php echo e($modalId); ?>();
            loadCurrentDirectory<?php echo e($modalId); ?>();
        } else {
            showNotification(data.message || 'Erro ao criar pasta', 'error');
        }
    } catch (error) {
        console.error('Erro:', error);
        showNotification('Erro ao criar pasta', 'error');
    }
}

// Função de notificação simples (pode ser melhorada)
function showNotification(message, type = 'info') {
    // Você pode usar o sistema de notificação existente ou criar um toast simples
    alert(message);
}

// Funções de Seleção Múltipla
function handleFileClick<?php echo e($modalId); ?>(filePath, event) {
    const isCtrlPressed = event.ctrlKey || event.metaKey; // metaKey para Mac
    
    if (isCtrlPressed) {
        // Modo seleção múltipla
        toggleFileSelection<?php echo e($modalId); ?>(filePath);
    } else {
        // Seleção única (comportamento original)
        if (<?php echo e($modalId); ?>SelectedFiles.length === 0) {
            // Se não há seleções, selecionar apenas este arquivo
            selectSingleFile<?php echo e($modalId); ?>(filePath);
        } else {
            // Se há seleções múltiplas, limpar e selecionar apenas este
            <?php echo e($modalId); ?>SelectedFiles = [];
            selectSingleFile<?php echo e($modalId); ?>(filePath);
        }
    }
    
    updateMultiSelectUI<?php echo e($modalId); ?>();
}

function handleFileDoubleClick<?php echo e($modalId); ?>(filePath) {
    // Efeito visual de seleção
    const fileElement = document.querySelector(`[data-path="${filePath}"]`);
    if (fileElement) {
        fileElement.classList.add('animate-pulse', 'ring-2', 'ring-green-500');
        setTimeout(() => {
            fileElement.classList.remove('animate-pulse', 'ring-2', 'ring-green-500');
        }, 500);
    }
    
    // Duplo clique sempre seleciona a imagem e fecha o modal
    setTimeout(() => {
        <?php echo e($onSelectCallback); ?>(filePath);
        closeFileManager<?php echo e($modalId); ?>();
    }, 300); // Pequeno delay para mostrar o efeito visual
}

function toggleFileSelection<?php echo e($modalId); ?>(filePath) {
    const existingIndex = <?php echo e($modalId); ?>SelectedFiles.findIndex(file => file.path === filePath);
    
    if (existingIndex !== -1) {
        // Remover da seleção
        <?php echo e($modalId); ?>SelectedFiles.splice(existingIndex, 1);
    } else {
        // Adicionar à seleção
        const fileItem = <?php echo e($modalId); ?>AllItems.find(item => item.path === filePath);
        if (fileItem) {
            <?php echo e($modalId); ?>SelectedFiles.push(fileItem);
        }
    }
}

function selectSingleFile<?php echo e($modalId); ?>(filePath) {
    <?php echo e($modalId); ?>SelectedFiles = [];
    const fileItem = <?php echo e($modalId); ?>AllItems.find(item => item.path === filePath);
    if (fileItem) {
        <?php echo e($modalId); ?>SelectedFiles.push(fileItem);
    }
}

function updateMultiSelectUI<?php echo e($modalId); ?>() {
    const multiSelectBar = document.getElementById('<?php echo e($modalId); ?>MultiSelectBar');
    const selectedCount = document.getElementById('<?php echo e($modalId); ?>SelectedCount');
    const selectedFilesList = document.getElementById('<?php echo e($modalId); ?>SelectedFilesList');
    
    // Mostrar/ocultar barra de seleção múltipla
    if (<?php echo e($modalId); ?>SelectedFiles.length > 0) {
        multiSelectBar.classList.remove('hidden');
        selectedCount.textContent = <?php echo e($modalId); ?>SelectedFiles.length;
        
        // Lista de arquivos selecionados
        const fileNames = <?php echo e($modalId); ?>SelectedFiles.map(file => file.name).join(', ');
        selectedFilesList.textContent = fileNames;
    } else {
        multiSelectBar.classList.add('hidden');
    }
    
    // Atualizar visual dos itens
    renderItems<?php echo e($modalId); ?>(<?php echo e($modalId); ?>AllItems);
}

function clearAllSelections<?php echo e($modalId); ?>() {
    <?php echo e($modalId); ?>SelectedFiles = [];
    updateMultiSelectUI<?php echo e($modalId); ?>();
}

function confirmMultipleSelection<?php echo e($modalId); ?>() {
    if (<?php echo e($modalId); ?>SelectedFiles.length === 0) {
        showNotification('Nenhum arquivo selecionado', 'warning');
        return;
    }
    
    // Criar uma cópia dos arquivos selecionados para evitar problemas de referência
    const filesToProcess = [...<?php echo e($modalId); ?>SelectedFiles];
    
    // Processar arquivos sequencialmente para evitar conflitos
    let currentIndex = 0;
    
    function processNextFile() {
        if (currentIndex >= filesToProcess.length) {
            // Todos os arquivos foram processados, fechar modal
            const confirmBtn = document.getElementById('<?php echo e($modalId); ?>ConfirmBtn');
            if (confirmBtn) {
                confirmBtn.textContent = 'Selecionar Todos';
                confirmBtn.disabled = false;
            }
            clearAllSelections<?php echo e($modalId); ?>();
            closeFileManager<?php echo e($modalId); ?>();
            return;
        }
        
        const file = filesToProcess[currentIndex];
        
        // Atualizar botão com progresso
        const confirmBtn = document.getElementById('<?php echo e($modalId); ?>ConfirmBtn');
        if (confirmBtn) {
            confirmBtn.textContent = `Processando ${currentIndex + 1}/${filesToProcess.length}`;
            confirmBtn.disabled = true;
        }
        
        // Chamar callback para o arquivo atual
        <?php echo e($onSelectCallback); ?>(file.path);
        
        // Próximo arquivo após um pequeno delay
        currentIndex++;
        setTimeout(processNextFile, 150); // 150ms de delay entre cada arquivo
    }
    
    // Iniciar processamento
    processNextFile();
}

// Resetar seleções ao abrir o modal
function openFileManager<?php echo e($modalId); ?>() {
    const modal = document.getElementById('<?php echo e($modalId); ?>');
    modal.classList.remove('hidden');
    
    // Resetar seleções
    <?php echo e($modalId); ?>SelectedFiles = [];
    <?php echo e($modalId); ?>MultiSelectMode = false;
    
    // Resetar para a raiz e carregar
    <?php echo e($modalId); ?>CurrentDirectory = '';
    navigateToFolder<?php echo e($modalId); ?>('');
}
</script>

<?php /**PATH C:\xampp\htdocs\resources\views/components/file-manager-modal.blade.php ENDPATH**/ ?>