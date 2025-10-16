// =============================================================================
// FILE MANAGER PARA CAMPOS EXTRAS - products/create
// =============================================================================

let currentOptionIndex = null;
const fileManagerData = {
    currentPath: '',
    folders: [],
    files: [],
    breadcrumb: []
};

function openFileManagerForOption(optionIndex) {
    currentOptionIndex = optionIndex;
    const modal = document.getElementById('optionFileManagerModal');
    modal.classList.remove('hidden');
    loadFileManagerImages();
}

function closeOptionFileManager() {
    const modal = document.getElementById('optionFileManagerModal');
    modal.classList.add('hidden');
    currentOptionIndex = null;
}

async function loadFileManagerImages() {
    const loading = document.getElementById('optionFileManagerLoading');
    const grid = document.getElementById('optionFileManagerGrid');
    
    if (!loading || !grid) {
        console.error('❌ Elementos do modal não encontrados!');
        return;
    }
    
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    
    fileManagerData.currentPath = '';
    await loadCurrentDirectory();
}

async function loadCurrentDirectory() {
    
    const loading = document.getElementById('optionFileManagerLoading');
    const grid = document.getElementById('optionFileManagerGrid');
    
    if (!loading || !grid) {
        console.error('❌ Elementos do modal não encontrados!');
        return;
    }
    
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    
    try {
        let url = window.fileManagerRoute || '/admin/file-manager/api';
        
        if (fileManagerData.currentPath && fileManagerData.currentPath.trim() !== '') {
            url += `?directory=${encodeURIComponent(fileManagerData.currentPath)}`;
        }
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        if (data.items) {
            fileManagerData.folders = data.items.filter(item => item.type === 'directory');
            fileManagerData.files = data.items.filter(item => 
                item.type === 'file' && isImageFile(item.name)
            );
        } else {
            fileManagerData.folders = [];
            fileManagerData.files = [];
        }
        
        updateBreadcrumb();
        displayFileManagerContent();
    } catch (error) {
        console.error('❌ Erro ao carregar diretório:', error);
        alert('Erro ao carregar diretório: ' + error.message);
    }
}

function updateBreadcrumb() {
    const parts = fileManagerData.currentPath ? fileManagerData.currentPath.split('/').filter(p => p) : [];
    fileManagerData.breadcrumb = parts.map((part, index) => ({
        name: part,
        path: parts.slice(0, index + 1).join('/')
    }));
}

function displayFileManagerContent() {
    const grid = document.getElementById('optionFileManagerGrid');
    const loading = document.getElementById('optionFileManagerLoading');
    
    if (!grid || !loading) {
        console.error('❌ Elementos principais do modal não encontrados!');
        return;
    }
    
    loading.classList.add('hidden');
    grid.classList.remove('hidden');
    
    const foldersSection = document.getElementById('foldersSection');
    const filesSection = document.getElementById('filesSection');
    const foldersGrid = document.getElementById('foldersGrid');
    const filesGrid = document.getElementById('filesGrid');
    
    updateBreadcrumbDOM();
    
    if (foldersSection) {
        foldersSection.style.display = fileManagerData.folders.length > 0 ? 'block' : 'none';
    }
    if (filesSection) {
        filesSection.style.display = fileManagerData.files.length > 0 ? 'block' : 'none';
    }
    
    if (fileManagerData.folders.length > 0 && foldersGrid) {
        foldersGrid.innerHTML = fileManagerData.folders.map(folder => `
            <div class="relative group cursor-pointer rounded-lg border-2 border-gray-200 hover:border-blue-500 hover:shadow-lg transition-all p-4 bg-white"
                 onclick="navigateToFolder('${folder.path}')"
                 title="Entrar em ${folder.name}">
                <div class="text-center">
                    <i class="bi bi-folder text-4xl text-blue-500 mb-3"></i>
                    <p class="text-sm font-medium text-gray-900 truncate">${folder.name}</p>
                    <p class="text-xs text-gray-500 mt-1">Pasta</p>
                </div>
            </div>
        `).join('');
    }
    
    if (fileManagerData.files.length > 0 && filesGrid) {
        filesGrid.innerHTML = fileManagerData.files.map(file => {
            const fileSize = formatFileSize(file.size);
            const fileExtension = file.name.split('.').pop().toUpperCase();
            
            return `
                <div class="relative group cursor-pointer rounded-lg overflow-hidden border-2 border-gray-200 hover:border-green-500 hover:shadow-lg transition-all aspect-square bg-gray-50"
                     onclick="selectImageForOption('${file.path}')"
                     title="${file.name} (${fileSize})">
                    <img src="${file.url}" 
                         alt="${file.name}"
                         class="w-full h-full object-cover"
                         loading="lazy"
                         onerror="this.src='/images/general/callback-image.svg'">
                    
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all flex items-center justify-center">
                        <i class="bi bi-check-circle-fill text-white text-3xl opacity-0 group-hover:opacity-100 transition-opacity"></i>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-2">
                        <p class="text-white text-xs font-medium truncate mb-1">${file.name}</p>
                        <div class="flex justify-between items-center text-xs text-gray-300">
                            <span>${fileExtension}</span>
                            <span>${fileSize}</span>
                        </div>
                    </div>
                    
                    <div class="absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-full">
                        ${fileExtension}
                    </div>
                </div>
            `;
        }).join('');
    }
    
    if (fileManagerData.folders.length === 0 && fileManagerData.files.length === 0) {
        grid.innerHTML = `
            <div class="text-center py-12">
                <i class="bi bi-folder-x text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pasta vazia</h3>
                <p class="text-gray-500">Esta pasta não contém arquivos ou subpastas.</p>
            </div>
        `;
    }
}

function updateBreadcrumbDOM() {
    const breadcrumbContainer = document.getElementById('breadcrumbContainer');
    if (!breadcrumbContainer) return;
    
    let breadcrumbHTML = `
        <button onclick="navigateToFolder('')" 
                class="hover:text-blue-600 flex items-center gap-1 transition-colors">
            <i class="bi bi-house"></i>
            <span>Raiz</span>
        </button>
    `;
    
    fileManagerData.breadcrumb.forEach(part => {
        breadcrumbHTML += `
            <div class="flex items-center space-x-2">
                <i class="bi bi-chevron-right text-gray-400"></i>
                <button onclick="navigateToFolder('${part.path}')" 
                        class="hover:text-blue-600 transition-colors">
                    ${part.name}
                </button>
            </div>
        `;
    });
    
    breadcrumbContainer.innerHTML = breadcrumbHTML;
}

function navigateToFolder(path) {
    fileManagerData.currentPath = path;
    loadCurrentDirectory();
}

function selectImageForOption(imagePath) {
    if (currentOptionIndex === null) {
        console.error('❌ currentOptionIndex é null');
        return;
    }
    
    const cleanPath = imagePath.startsWith('public/') ? imagePath.replace('public/', '') : imagePath;
    
    // Atualizar o input e preview da imagem
    const inputField = document.querySelector(`input[name="options[${currentOptionIndex}][image_url]"]`);
    const previewImg = document.querySelector(`#preview_${currentOptionIndex}`);
    
    if (inputField) {
        inputField.value = cleanPath;
    }
    
    if (previewImg) {
        previewImg.src = `/images/${cleanPath}`;
        previewImg.classList.remove('hidden');
    }
    
    closeOptionFileManager();
}

function isImageFile(filename) {
    const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
    const extension = filename.split('.').pop().toLowerCase();
    return imageExtensions.includes(extension);
}

function formatFileSize(bytes) {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
}

// Upload Modal Functions
function openUploadModal() {
    const modal = document.getElementById('uploadModal');
    modal.classList.remove('hidden');
    resetUploadModal();
}

function closeUploadModal() {
    const modal = document.getElementById('uploadModal');
    modal.classList.add('hidden');
    resetUploadModal();
}

function resetUploadModal() {
    const form = document.getElementById('uploadForm');
    if (form) form.reset();
    
    const uploadContent = document.getElementById('uploadContent');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadSuccess = document.getElementById('uploadSuccess');
    const progressBar = document.getElementById('progressBar');
    const uploadButton = document.getElementById('uploadButton');
    
    if (uploadContent) uploadContent.classList.remove('hidden');
    if (uploadProgress) uploadProgress.classList.add('hidden');
    if (uploadSuccess) uploadSuccess.classList.add('hidden');
    if (progressBar) progressBar.style.width = '0%';
    if (uploadButton) uploadButton.disabled = false;
}

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        if (!file.type.startsWith('image/')) {
            alert('Por favor, selecione apenas arquivos de imagem.');
            event.target.value = '';
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            alert('O arquivo deve ter no máximo 5MB.');
            event.target.value = '';
            return;
        }
        
        showFilePreview(file);
    }
}

function showFilePreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const uploadContent = document.getElementById('uploadContent');
        if (uploadContent) {
            uploadContent.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="w-32 h-32 object-cover rounded-lg mx-auto mb-4">
                <p class="text-gray-600 font-medium">${file.name}</p>
                <p class="text-sm text-gray-500">${formatFileSize(file.size)}</p>
            `;
        }
    };
    reader.readAsDataURL(file);
}

async function handleUpload(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const fileInput = document.getElementById('imageFileInput');
    
    if (!fileInput || !fileInput.files[0]) {
        alert('Por favor, selecione um arquivo para upload.');
        return;
    }
    
    const uploadContent = document.getElementById('uploadContent');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadButton = document.getElementById('uploadButton');
    
    if (uploadContent) uploadContent.classList.add('hidden');
    if (uploadProgress) uploadProgress.classList.remove('hidden');
    if (uploadButton) uploadButton.disabled = true;
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const uploadRoute = window.fileManagerUploadRoute || '/admin/file-manager/upload';
        
        const response = await fetch(uploadRoute, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            const uploadSuccess = document.getElementById('uploadSuccess');
            if (uploadProgress) uploadProgress.classList.add('hidden');
            if (uploadSuccess) uploadSuccess.classList.remove('hidden');
            
            setTimeout(() => {
                loadCurrentDirectory();
                closeUploadModal();
            }, 1000);
        } else {
            throw new Error(result.message || 'Erro no upload');
        }
    } catch (error) {
        console.error('❌ Erro no upload:', error);
        alert('Erro no upload: ' + error.message);
        resetUploadModal();
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', handleUpload);
    }
});

