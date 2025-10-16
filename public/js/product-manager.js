// Função Alpine.js para gerenciar produtos
function productManager() {
    return {
        // Estados
        featuredImage: (() => {
            const path = window.productFeaturedImagePath || window.productFeaturedImage;
            if (!path) return null;
            
            // Gerar URL correta
            if (path.startsWith('http')) return path;
            if (path.startsWith('/')) return path;
            if (path.startsWith('storage/') || path.startsWith('images/')) {
                return '/' + path;
            }
            return '/images/' + path;
        })(),
        featuredImagePath: window.productFeaturedImagePath || '',
        galleryImages: (window.productGalleryImages || []).map(img => {
            // Normalizar URLs das imagens da galeria
            if (typeof img === 'string') {
                const url = img.startsWith('http') ? img : 
                           img.startsWith('/') ? img :
                           img.startsWith('storage/') || img.startsWith('images/') ? '/' + img :
                           '/images/' + img;
                return {
                    url: url,
                    path: img,
                    name: img.split('/').pop()
                };
            }
            // Se já é objeto
            if (img.url) {
                const url = img.url.startsWith('http') ? img.url :
                           img.url.startsWith('/') ? img.url :
                           img.url.startsWith('storage/') || img.url.startsWith('images/') ? '/' + img.url :
                           '/images/' + img.url;
                return {
                    ...img,
                    url: url
                };
            }
            return img;
        }),
        showFileManager: false,
        showNewCategoryModal: false,
        newCategoryName: '',
        creatingCategory: false,
        aiLoading: false,
        fileManagerType: 'featured', // 'featured' ou 'gallery'
        
        // File Manager
        fileManagerLoading: false,
        fileManagerItems: window.files || [],
        groupedFiles: {},
        selectedFiles: [],
        
        // Drag and Drop
        draggedIndex: null,
        
        // SEO Preview
        showSeoPreview: false,
        seoData: {
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
            slug: ''
        },
        
        init() {
            // Inicializar drag and drop para galeria
            this.initializeDragAndDrop();
            
            // Inicializar dados SEO
            this.initializeSeoData();
            
            // Observar mudanças no featuredImagePath
            this.$watch('featuredImagePath', (value) => {
                console.log('🔄 featuredImagePath changed to:', value);
            });
            
            // Observar mudanças no featuredImage
            this.$watch('featuredImage', (value) => {
                console.log('🔄 featuredImage changed to:', value);
            });
            
            // Log de inicialização (apenas em desenvolvimento)
            if (this.featuredImage || this.galleryImages.length > 0) {
                console.log('✅ Produto carregado com imagens:', {
                    featured: !!this.featuredImage,
                    gallery: this.galleryImages.length
                });
            }
        },
        
        // Gerenciamento de Imagem Destacada
        openFileManager(type) {
            this.fileManagerType = type;
            this.showFileManager = true;
            this.loadFileManager();
        },
        
        async loadFileManager() {
            this.fileManagerLoading = true;
            this.selectedFiles = []; // Limpar seleção ao abrir
            
            // Processar arquivos para agrupamento
            this.processFiles();
            
            setTimeout(() => {
                this.fileManagerLoading = false;
            }, 500);
        },
        
        processFiles() {
            // Filtrar apenas imagens
            const images = this.fileManagerItems.filter(file => 
                file.type === 'file' && 
                file.extension && 
                ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico'].includes(file.extension.toLowerCase())
            );
            
            // Agrupar por pasta
            this.groupedFiles = images.reduce((groups, file) => {
                const folder = file.folder || 'Raiz';
                if (!groups[folder]) {
                    groups[folder] = [];
                }
                groups[folder].push(file);
                return groups;
            }, {});
        },
        
        // Métodos para seleção múltipla
        toggleFileSelection(file) {
            const index = this.selectedFiles.findIndex(f => f.path === file.path);
            if (index > -1) {
                this.selectedFiles.splice(index, 1);
            } else {
                this.selectedFiles.push(file);
            }
        },
        
        selectAllFiles() {
            // Coletar todos os arquivos de todas as pastas
            const allFiles = Object.values(this.groupedFiles).flat();
            this.selectedFiles = [...allFiles];
        },
        
        clearSelection() {
            this.selectedFiles = [];
        },
        
        confirmGallerySelection() {
            if (this.selectedFiles.length === 0) return;
            
            // Adicionar apenas arquivos que não existem na galeria
            this.selectedFiles.forEach(file => {
                const exists = this.galleryImages.some(img => img.path === file.path);
                if (!exists) {
                    this.galleryImages.push({
                        path: file.path,
                        url: file.url,
                        name: file.name,
                        preview: file.url
                    });
                }
            });
            
            console.log('Gallery updated:', this.galleryImages);
            
            // Fechar modal
            this.showFileManager = false;
            
            // Mostrar notificação
            this.showNotification(`${this.selectedFiles.length} imagens adicionadas à galeria!`, 'success');
        },
        
        // Métodos de Drag and Drop
        handleDragStart(event, index) {
            this.draggedIndex = index;
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', index.toString());
            
            // Adicionar classe de feedback visual
            event.target.style.opacity = '0.5';
            
            console.log('Drag started:', index);
        },
        
        handleDragEnd(event) {
            this.draggedIndex = null;
            
            // Remover classe de feedback visual
            event.target.style.opacity = '1';
        },
        
        handleDrop(event) {
            event.preventDefault();
            
            if (this.draggedIndex === null) return;
            
            console.log('Drop event triggered');
            
            // Encontrar o elemento de destino
            const dropTarget = event.target.closest('[draggable="true"]');
            
            if (!dropTarget) {
                console.log('No drop target found');
                return;
            }
            
            // Encontrar o índice do elemento de destino
            const allItems = Array.from(document.querySelectorAll('[draggable="true"]'));
            const dropIndex = allItems.indexOf(dropTarget);
            
            console.log('Drop target index:', dropIndex, 'Dragged index:', this.draggedIndex);
            
            if (dropIndex === -1 || dropIndex === this.draggedIndex) {
                console.log('Invalid drop operation');
                return;
            }
            
            // Reordenar array
            this.reorderGalleryImages(this.draggedIndex, dropIndex);
            
            // Mostrar notificação
            this.showNotification('Ordem das imagens atualizada!', 'success');
        },
        
        
        reorderGalleryImages(fromIndex, toIndex) {
            // Remover o item da posição original
            const [movedItem] = this.galleryImages.splice(fromIndex, 1);
            
            // Inserir na nova posição
            this.galleryImages.splice(toIndex, 0, movedItem);
            
            console.log('Gallery reordered:', this.galleryImages);
        },
        
        selectFile(fileData) {
            console.log('🎯 selectFile called with:', fileData);
            if (this.fileManagerType === 'featured') {
                this.featuredImage = fileData.url;
                this.featuredImagePath = fileData.path;
                console.log('✅ Featured image set:', {
                    url: this.featuredImage,
                    path: this.featuredImagePath
                });
            } else if (this.fileManagerType === 'gallery') {
                // Adicionar à galeria se não existir
                const exists = this.galleryImages.some(img => img.path === fileData.path);
                if (!exists) {
                    this.galleryImages.push({
                        path: fileData.path,
                        url: fileData.url,
                        name: fileData.name
                    });
                    console.log('Gallery image added:', this.galleryImages);
                }
            }
            
            // Fechar modal
            this.showFileManager = false;
            
            // Mostrar notificação
            this.showNotification(`Imagem "${fileData.name}" selecionada!`, 'success');
        },
        
        removeFeaturedImage() {
            console.log('🗑️ Removendo imagem destacada...');
            this.featuredImage = null;
            this.featuredImagePath = '';
            console.log('✅ Imagem destacada removida');
        },
        
        removeGalleryImage(index) {
            this.galleryImages.splice(index, 1);
        },
        
        getFileIcon(extension) {
            const icons = {
                'jpg': 'bi bi-file-earmark-image text-green-500',
                'jpeg': 'bi bi-file-earmark-image text-green-500',
                'png': 'bi bi-file-earmark-image text-green-500',
                'gif': 'bi bi-file-earmark-image text-green-500',
                'svg': 'bi bi-file-earmark-image text-green-500',
                'webp': 'bi bi-file-earmark-image text-green-500',
                'bmp': 'bi bi-file-earmark-image text-green-500',
                'ico': 'bi bi-file-earmark-image text-green-500',
                'pdf': 'bi bi-file-earmark-pdf text-red-500',
                'doc': 'bi bi-file-earmark-word text-blue-500',
                'docx': 'bi bi-file-earmark-word text-blue-500',
                'xls': 'bi bi-file-earmark-excel text-green-500',
                'xlsx': 'bi bi-file-earmark-excel text-green-500',
                'txt': 'bi bi-file-earmark-text text-gray-500',
                'zip': 'bi bi-file-earmark-zip text-orange-500',
                'rar': 'bi bi-file-earmark-zip text-orange-500'
            };
            return icons[extension?.toLowerCase()] || 'bi bi-file-earmark text-gray-500';
        },
        
        showNotification(message, type = 'info') {
            // Criar notificação simples
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remover após 3 segundos
            setTimeout(() => {
                notification.remove();
            }, 3000);
        },
        
        // Drag and Drop para Galeria
        initializeDragAndDrop() {
            this.$nextTick(() => {
                const galleryContainer = document.getElementById('gallery-container');
                if (galleryContainer && typeof Sortable !== 'undefined') {
                    new Sortable(galleryContainer, {
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        onEnd: (evt) => {
                            const item = this.galleryImages.splice(evt.oldIndex, 1)[0];
                            this.galleryImages.splice(evt.newIndex, 0, item);
                        }
                    });
                }
            });
        },
        
        // Categorias
        createNewCategory() {
            this.showNewCategoryModal = true;
        },
        
        async addNewCategoryToList() {
            if (!this.newCategoryName.trim()) return;
            
            this.creatingCategory = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(window.categoryStoreRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.newCategoryName
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    // Adicionar nova categoria à lista
                    const categoryList = document.getElementById('categories-list');
                    if (categoryList) {
                        // Verificar se estamos em create ou edit pelo tipo de input
                        const isCreateForm = categoryList.querySelector('input[name="categories[]"]');
                        const isEditForm = categoryList.querySelector('input[name="category_id[]"]');
                        
                        let newCategoryHtml = '';
                        
                        if (isCreateForm) {
                            // Formulário de criação
                            newCategoryHtml = `
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="category_${data.category.id}" 
                                           name="categories[]" 
                                           value="${data.category.id}"
                                           checked
                                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                    <label for="category_${data.category.id}" class="ml-2 block text-sm text-gray-900">
                                        ${data.category.name}
                                    </label>
                                </div>
                            `;
                        } else {
                            // Formulário de edição
                            newCategoryHtml = `
                                <label class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                                    <input type="checkbox" 
                                           name="category_id[]" 
                                           value="${data.category.id}"
                                           checked
                                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                    <span class="text-sm text-gray-900">${data.category.name}</span>
                                </label>
                            `;
                        }
                        
                        categoryList.insertAdjacentHTML('beforeend', newCategoryHtml);
                    }
                    
                    this.showNewCategoryModal = false;
                    this.newCategoryName = '';
                    this.showNotification(`Categoria "${data.category.name}" criada com sucesso!`, 'success');
                } else {
                    this.showNotification('Erro ao criar categoria: ' + (data.message || 'Erro desconhecido'), 'error');
                }
            } catch (error) {
                console.error('Erro ao criar categoria:', error);
                this.showNotification('Erro ao conectar com o servidor', 'error');
            } finally {
                this.creatingCategory = false;
            }
        },
        
        // IA Assistant
        async generateDescriptionWithAI() {
            const productName = document.getElementById('name')?.value;
            if (!productName) {
                alert('Por favor, insira o nome do produto primeiro');
                return;
            }
            
            this.aiLoading = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch(window.generateDescriptionRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        name: productName
                    })
                });
                
                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json') ? await response.json() : { success: false, message: 'Erro inesperado do servidor' };
                if (!response.ok) {
                    const msg = (data && data.message) ? data.message : `Erro ${response.status}`;
                    alert('Erro ao gerar descrição: ' + msg);
                    this.aiLoading = false;
                    return;
                }
                if (data.success) {
                    // Preencher campos
                    if (data.short_description) {
                        const shortDescField = document.getElementById('short_description');
                        if (shortDescField) shortDescField.value = data.short_description;
                    }
                    
                    if (data.description) {
                        const descField = document.getElementById('description');
                        if (descField) descField.value = data.description;
                    }
                    
                    if (data.seo_tags) {
                        const seoTagsField = document.getElementById('seo_tags');
                        if (seoTagsField) seoTagsField.value = data.seo_tags;
                    }
                    
                    // Preencher campos SEO
                    if (data.meta_title) {
                        const metaTitleField = document.getElementById('meta_title');
                        if (metaTitleField) metaTitleField.value = data.meta_title;
                    }
                    
                    if (data.meta_description) {
                        const metaDescField = document.getElementById('meta_description');
                        if (metaDescField) metaDescField.value = data.meta_description;
                    }
                    
                    if (data.meta_keywords) {
                        const metaKeywordsField = document.getElementById('meta_keywords');
                        if (metaKeywordsField) {
                            metaKeywordsField.value = data.meta_keywords;
                            // Disparar evento para atualizar as tags
                            metaKeywordsField.dispatchEvent(new CustomEvent('keywords-updated', { 
                                detail: { keywords: data.meta_keywords }
                            }));
                        }
                    }
                    
                    // Preencher campo SKU
                    const skuField = document.getElementById('sku');
                    if (skuField) {
                        skuField.value = data.sku || '';
                        console.log('SKU preenchido:', skuField.value);
                    } else {
                        console.error('Campo SKU não encontrado!');
                    }
                    
                    // Atualizar preview SEO
                    this.updateSeoPreview();
                    
                    console.log('Conteúdo gerado com sucesso:', data);
                } else {
                    alert('Erro ao gerar descrição: ' + (data.message || 'Erro desconhecido'));
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                alert('Erro ao gerar descrição');
            } finally {
                this.aiLoading = false;
            }
        },
        
        // SEO Preview
        initializeSeoData() {
            // Preencher dados SEO existentes se houver
            const metaTitle = document.getElementById('meta_title')?.value || '';
            const metaDescription = document.getElementById('meta_description')?.value || '';
            const metaKeywords = document.getElementById('meta_keywords')?.value || '';
            
            this.seoData = {
                meta_title: metaTitle,
                meta_description: metaDescription,
                meta_keywords: metaKeywords,
                slug: this.generateSlug(document.getElementById('name')?.value || '')
            };
        },
        
        toggleSeoPreview() {
            this.showSeoPreview = !this.showSeoPreview;
        },
        
        updateSeoPreview() {
            const metaTitle = document.getElementById('meta_title')?.value || '';
            const metaDescription = document.getElementById('meta_description')?.value || '';
            const metaKeywords = document.getElementById('meta_keywords')?.value || '';
            const productName = document.getElementById('name')?.value || '';
            
            this.seoData = {
                meta_title: metaTitle,
                meta_description: metaDescription,
                meta_keywords: metaKeywords,
                slug: this.generateSlug(productName)
            };
        },
        
        generateMetaTitle(productName) {
            const baseTitle = productName || 'Produto';
            const suffix = ' | Laser Link';
            const maxLength = 60;
            
            if (baseTitle.length + suffix.length <= maxLength) {
                return baseTitle + suffix;
            }
            
            const availableLength = maxLength - suffix.length;
            return baseTitle.substring(0, availableLength - 3) + '...' + suffix;
        },
        
        generateSlug(name) {
            return name
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
        }
    }
}

// Tornar a função globalmente acessível
window.productManager = productManager;