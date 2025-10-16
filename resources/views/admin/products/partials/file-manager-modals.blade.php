<!-- Modal do File Manager para Campos Extras -->
<div id="optionFileManagerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 mx-auto p-0 border w-11/12 max-w-6xl shadow-lg rounded-2xl bg-white">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-white rounded-t-2xl">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Selecionar Imagem</h3>
                <p class="text-sm text-gray-600">Escolha uma imagem para a opção</p>
            </div>
            <button onclick="closeOptionFileManager()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-x text-gray-600 text-lg"></i>
            </button>
        </div>
        
        <!-- File Manager Content -->
        <div class="flex-1 p-6 overflow-hidden">
            <!-- Breadcrumb Navigation -->
            <div class="mb-4">
                <div id="breadcrumbContainer" class="flex items-center space-x-2 text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                    <button onclick="navigateToFolder('')" 
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
                               id="imageSearchInput"
                               placeholder="Buscar imagens..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Ações -->
                <div class="flex gap-2">
                    <!-- Botão Upload -->
                    <button onclick="openUploadModal()" 
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <i class="bi bi-upload"></i>
                        <span class="hidden sm:inline">Upload</span>
                    </button>
                    
                    <!-- Botão Atualizar -->
                    <button onclick="loadCurrentDirectory()" 
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading -->
            <div id="optionFileManagerLoading" class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="bi bi-image text-2xl text-blue-600"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Carregando imagens...</p>
                </div>
            </div>
            
            <!-- File Grid -->
            <div id="optionFileManagerGrid" class="hidden h-full overflow-y-auto">
                <!-- Pastas -->
                <div id="foldersSection" class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-folder text-blue-500 mr-2"></i>
                        Pastas
                    </h4>
                    <div id="foldersGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                </div>
                
                <!-- Arquivos -->
                <div id="filesSection" class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-file-image text-green-500 mr-2"></i>
                        Imagens
                    </h4>
                    <div id="filesGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <!-- Será preenchido via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
            <div class="text-sm text-gray-600">
                <i class="bi bi-info-circle mr-1"></i>
                Clique em uma imagem para selecionar
            </div>
            <button onclick="closeOptionFileManager()" 
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                Cancelar
            </button>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-60">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">📤 Upload de Imagem</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pasta de Destino</label>
                    <select name="directory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Raiz</option>
                        <option value="products">products</option>
                        <option value="banners">banners</option>
                        <option value="categories">categories</option>
                        <option value="logos">logos</option>
                        <option value="general">general</option>
                    </select>
                </div>
                
                <div id="uploadContent">
                    <div id="uploadArea" 
                         class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer"
                         onclick="document.getElementById('imageFileInput').click()">
                        <i class="bi bi-cloud-upload text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600 mb-2">Clique ou arraste uma imagem aqui</p>
                        <p class="text-sm text-gray-500">PNG, JPG, GIF até 5MB</p>
                        <input type="file" 
                               id="imageFileInput" 
                               name="file" 
                               accept="image/*" 
                               class="hidden"
                               onchange="handleFileSelect(event)">
                    </div>
                </div>
                
                <div id="uploadProgress" class="hidden">
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                        <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p class="text-sm text-gray-600">Enviando arquivo...</p>
                </div>
                
                <div id="uploadSuccess" class="hidden text-center">
                    <i class="bi bi-check-circle text-green-500 text-4xl mb-2"></i>
                    <p class="text-green-600 font-medium">Upload realizado com sucesso!</p>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" 
                            onclick="closeUploadModal()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            id="uploadButton"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
