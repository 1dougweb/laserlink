// Função Alpine.js para gerenciar posts
function postManager() {
    return {
        // Estados
        featuredImage: window.postFeaturedImage || null,
        featuredImagePath: (() => {
            if (!window.postFeaturedImage) return '';
            const url = window.postFeaturedImage;
            const storageIndex = url.indexOf('/storage/');
            return storageIndex !== -1 ? url.substring(storageIndex + 9) : '';
        })(),
        showFileManager: false,
        aiLoading: false,
        fileManagerType: 'featured',
        
        // File Manager
        fileManagerLoading: false,
        fileManagerItems: window.files || [],
        groupedFiles: {},
        selectedFiles: [],
        
        // SEO Preview
        showSeoPreview: false,
        seoData: {
            meta_title: '',
            meta_description: '',
            meta_keywords: '',
            slug: ''
        },
        
        init() {
            this.initializeSeoData();
            
            if (this.featuredImage) {
                console.log('✅ Post carregado com imagem destacada');
            }
            
            // Escutar evento customizado de seleção de imagem
            window.addEventListener('featuredImageSelected', (event) => {
                console.log('🎧 Evento featuredImageSelected recebido:', event.detail);
                this.featuredImage = event.detail.imageUrl;
                this.featuredImagePath = event.detail.filePath;
            });
        },
        
        // Gerenciamento de Imagem Destacada
        openFileManager(type) {
            this.fileManagerType = type;
            this.showFileManager = true;
            this.loadFileManager();
        },
        
        async loadFileManager() {
            this.fileManagerLoading = true;
            this.selectedFiles = [];
            this.processFiles();
            
            setTimeout(() => {
                this.fileManagerLoading = false;
            }, 500);
        },
        
        processFiles() {
            const images = this.fileManagerItems.filter(file => 
                file.type === 'file' && 
                file.extension && 
                ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico'].includes(file.extension.toLowerCase())
            );
            
            this.groupedFiles = images.reduce((groups, file) => {
                const folder = file.folder || 'Raiz';
                if (!groups[folder]) {
                    groups[folder] = [];
                }
                groups[folder].push(file);
                return groups;
            }, {});
        },
        
        selectFile(file) {
            this.featuredImage = file.url;
            this.featuredImagePath = file.path;
            this.showFileManager = false;
            console.log('✅ Imagem destacada selecionada:', file.path);
        },
        
        removeFeaturedImage() {
            this.featuredImage = null;
            this.featuredImagePath = '';
        },
        
        getFileIcon(extension) {
            const ext = (extension || '').toLowerCase();
            const iconMap = {
                'jpg': 'bi bi-file-image text-blue-500',
                'jpeg': 'bi bi-file-image text-blue-500',
                'png': 'bi bi-file-image text-green-500',
                'gif': 'bi bi-file-image text-purple-500',
                'svg': 'bi bi-file-image text-orange-500',
                'webp': 'bi bi-file-image text-teal-500',
            };
            return iconMap[ext] || 'bi bi-file-earmark text-gray-500';
        },
        
        // Geração de Conteúdo com IA
        async generateContentWithAI() {
            const postTitle = document.getElementById('title')?.value;
            const category = document.querySelector('select[name="category_id"] option:checked')?.text;
            
            if (!postTitle) {
                alert('Por favor, insira o título do post primeiro');
                return;
            }
            
            this.aiLoading = true;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const requestData = {
                    title: postTitle,
                    category: category
                };
                
                console.log('🚀 Enviando requisição para IA:', {
                    url: window.generateContentRoute,
                    data: requestData,
                    hasCsrfToken: !!csrfToken,
                    csrfTokenLength: csrfToken ? csrfToken.length : 0,
                    csrfTokenPreview: csrfToken ? csrfToken.substring(0, 10) + '...' : 'null'
                });
                
                const response = await fetch(window.generateContentRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(requestData)
                });
                
                console.log('📡 Resposta recebida:', {
                    status: response.status,
                    statusText: response.statusText,
                    ok: response.ok
                });
                
                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json') ? await response.json() : { success: false, message: 'Erro inesperado do servidor' };
                
                console.log('📦 Dados da resposta:', data);
                
                if (!response.ok) {
                    const msg = (data && data.message) ? data.message : `Erro ${response.status}`;
                    console.error('❌ Erro:', data);
                    alert('Erro ao gerar conteúdo: ' + msg);
                    this.aiLoading = false;
                    return;
                }
                
                if (data.success) {
                    // Preencher excerpt
                    if (data.excerpt) {
                        const excerptField = document.getElementById('excerpt');
                        if (excerptField) excerptField.value = data.excerpt;
                    }
                    
                    // Preencher conteúdo no TinyMCE
                    if (data.content && typeof tinymce !== 'undefined') {
                        const editor = tinymce.get('content');
                        if (editor) {
                            editor.setContent(data.content);
                        } else {
                            const contentField = document.getElementById('content');
                            if (contentField) contentField.value = data.content;
                        }
                    }
                    
                    // Preencher campos SEO
                    if (data.meta_title) {
                        const metaTitleField = document.getElementById('meta_title');
                        if (metaTitleField) {
                            metaTitleField.value = data.meta_title;
                            this.seoData.meta_title = data.meta_title;
                        }
                    }
                    
                    if (data.meta_description) {
                        const metaDescField = document.getElementById('meta_description');
                        if (metaDescField) {
                            metaDescField.value = data.meta_description;
                            this.seoData.meta_description = data.meta_description;
                        }
                    }
                    
                    if (data.meta_keywords) {
                        const metaKeywordsField = document.getElementById('meta_keywords');
                        if (metaKeywordsField) {
                            metaKeywordsField.value = data.meta_keywords;
                            this.seoData.meta_keywords = data.meta_keywords;
                            
                            // Dispatch event for tags component
                            metaKeywordsField.dispatchEvent(new CustomEvent('keywords-updated', {
                                detail: { keywords: data.meta_keywords }
                            }));
                        }
                    }
                    
                    alert('✅ Conteúdo gerado com sucesso pela IA!');
                } else {
                    alert('Erro: ' + (data.message || 'Não foi possível gerar o conteúdo'));
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao gerar conteúdo: ' + error.message);
            } finally {
                this.aiLoading = false;
            }
        },
        
        // SEO Preview
        initializeSeoData() {
            this.seoData.meta_title = document.getElementById('meta_title')?.value || document.getElementById('title')?.value || '';
            this.seoData.meta_description = document.getElementById('meta_description')?.value || '';
            this.seoData.meta_keywords = document.getElementById('meta_keywords')?.value || '';
            this.seoData.slug = document.getElementById('slug')?.value || '';
            
            this.$watch('seoData.meta_title', (value) => {
                const field = document.getElementById('meta_title');
                if (field && field.value !== value) {
                    field.value = value;
                }
            });
            
            this.$watch('seoData.meta_description', (value) => {
                const field = document.getElementById('meta_description');
                if (field && field.value !== value) {
                    field.value = value;
                }
            });
            
            this.$watch('seoData.meta_keywords', (value) => {
                const field = document.getElementById('meta_keywords');
                if (field && field.value !== value) {
                    field.value = value;
                }
            });
            
            this.$watch('seoData.slug', (value) => {
                const field = document.getElementById('slug');
                if (field && field.value !== value) {
                    field.value = value;
                }
            });
        },
        
        updateSeoPreview(field, value) {
            this.seoData[field] = value;
        },
        
        toggleSeoPreview() {
            this.showSeoPreview = !this.showSeoPreview;
        }
    };
}

// Nota: A função selectFeaturedImage() agora é definida inline em cada página (create.blade.php e edit.blade.php)
// para garantir que esteja disponível antes do componente file-manager-modal carregar

