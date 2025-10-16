@extends('admin.layout')

@section('title', 'Aparência - Laser Link')
@section('page-title', 'Aparência')

@section('content')
<div class="space-y-6">
    <form method="POST" action="{{ route('admin.settings.appearance.update') }}" enctype="multipart/form-data" x-data="appearanceForm()" x-init="init()">
        @csrf
        @method('PUT')
        
        <!-- Campos hidden para URLs das imagens selecionadas -->
        <input type="hidden" id="image_url" name="image_url" value="">
        <input type="hidden" id="sidebar_image_url" name="sidebar_image_url" value="">
        <input type="hidden" id="site_image_url" name="site_image_url" value="">
        <input type="hidden" id="footer_image_url" name="footer_image_url" value="">
        <input type="hidden" id="favicon_image_url" name="favicon_image_url" value="">
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <i class="bi bi-image text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Logo do Sidebar</h3>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload da Logo
                    </label>
                    
                    <!-- Opções de seleção -->
                    <div class="mb-4 flex space-x-3">
                        <button type="button" 
                                onclick="openFileManagerlogoMainFileManager()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="bi bi-folder mr-2"></i>Selecionar do Gerenciador
                        </button>
                        <button type="button" 
                                onclick="document.getElementById('logo').click()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="bi bi-upload mr-2"></i>Upload Novo
                        </button>
                    </div>
                    
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="bi bi-cloud-upload text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="logo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Fazer upload</span>
                                    <input type="file" 
                                           id="logo" 
                                           name="logo" 
                                           accept="image/*"
                                           class="sr-only"
                                           @change="previewLogo($event)">
                                </label>
                                <p class="pl-1">ou arraste e solte</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF até 2MB</p>
                        </div>
                    </div>
                    @error('logo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                    <div class="border border-gray-200 rounded-lg p-4 py-8 bg-gray-500">
                        <div x-show="!logoPreview" class="text-center text-gray-500">
                            <i class="bi bi-image text-4xl mb-2"></i>
                            <p>Nenhuma imagem selecionada</p>
                        </div>
                        <div x-show="logoPreview" class="text-center">
                            <img :src="logoPreview" alt="Preview da logo" class="max-h-20 mx-auto">
                        </div>
                        
                    </div>
                </div>
            </div>

            <!-- Logo do Site Público -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center mb-6">
                    <i class="bi bi-globe text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Logo do Site Público</h3>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload da Logo do Site
                        </label>
                        
                        <!-- Opções de seleção -->
                        <div class="mb-4 flex space-x-3">
                            <button type="button" 
                                    onclick="openFileManagerlogoSiteFileManager()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="bi bi-folder mr-2"></i>Selecionar do Gerenciador
                            </button>
                            <button type="button" 
                                    onclick="document.getElementById('site_logo').click()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="bi bi-upload mr-2"></i>Upload Novo
                            </button>
                        </div>
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="bi bi-cloud-upload text-gray-400 text-4xl"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="site_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                        <span>Fazer upload</span>
                                        <input type="file" 
                                               id="site_logo" 
                                               name="site_logo" 
                                               accept="image/*"
                                               class="sr-only"
                                               @change="previewSiteLogo($event)">
                                    </label>
                                    <p class="pl-1">ou arraste e solte</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF até 2MB</p>
                            </div>
                        </div>
                        @error('site_logo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                    <div class="border border-gray-200 rounded-lg p-4 py-8 bg-gray-500">
                            <div x-show="!siteLogoPreview" class="text-center text-gray-500">
                                <i class="bi bi-image text-4xl mb-2"></i>
                                <p>Nenhuma imagem selecionada</p>
                            </div>
                            <div x-show="siteLogoPreview" class="text-center">
                                <img :src="siteLogoPreview" alt="Preview da logo do site" class="max-h-20 mx-auto">
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo do Sidebar do Cliente -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center mb-6">
                    <i class="bi bi-layout-sidebar text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Logo do Sidebar do Cliente</h3>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="sidebar_logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload da Logo do Sidebar
                        </label>
                        
                        <!-- Opções de seleção -->
                        <div class="mb-4 flex space-x-3">
                            <button type="button" 
                                    onclick="openFileManagerlogoSidebarFileManager()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="bi bi-folder mr-2"></i>Selecionar do Gerenciador
                            </button>
                            <button type="button" 
                                    onclick="document.getElementById('sidebar_logo').click()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="bi bi-upload mr-2"></i>Upload Novo
                            </button>
                        </div>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="bi bi-cloud-upload text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="sidebar_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Fazer upload</span>
                                    <input type="file" 
                                           id="sidebar_logo" 
                                           name="sidebar_logo" 
                                           accept="image/*"
                                           class="sr-only"
                                           @change="previewSidebarLogo($event)">
                                </label>
                            </div>
                        </div>
                        @error('sidebar_logo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                    <div class="border border-gray-200 rounded-lg p-4 py-8 bg-gray-500">
                            <div x-show="!sidebarLogoPreview" class="text-center text-gray-500">
                                <i class="bi bi-image text-4xl mb-2"></i>
                                <p>Nenhuma imagem selecionada</p>
                            </div>
                            <div x-show="sidebarLogoPreview" class="text-center">
                                <img :src="sidebarLogoPreview" alt="Preview da logo do sidebar" class="max-h-20 mx-auto">
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo do Blog -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center mb-6">
                    <i class="bi bi-newspaper text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Logo do Blog</h3>
                    <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">Novo</span>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="blog_logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload da Logo do Blog
                        </label>
                        
                        <!-- Opções de seleção -->
                        <div class="mb-4 flex space-x-3">
                            <button type="button" 
                                    onclick="openFileManagerlogoBlogFileManager()"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="bi bi-folder mr-2"></i>Selecionar do Gerenciador
                            </button>
                            <button type="button" 
                                    onclick="document.getElementById('blog_logo').click()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="bi bi-upload mr-2"></i>Upload Novo
                            </button>
                        </div>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                            <i class="bi bi-cloud-upload text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="blog_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Fazer upload</span>
                                    <input type="file" 
                                           id="blog_logo" 
                                           name="blog_logo" 
                                           accept="image/*"
                                           class="sr-only"
                                           @change="previewBlogLogo($event)">
                                </label>
                                <p class="pl-1">ou arraste e solte</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF, SVG, WEBP até 2MB</p>
                        </div>
                        @error('blog_logo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Hidden input para receber o caminho do gerenciador de arquivos -->
                        <input type="hidden" name="blog_image_url" id="blog_image_url" value="">
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <div class="border border-gray-200 rounded-lg p-4 py-8 bg-gray-800">
                            <div x-show="!blogLogoPreview" class="text-center text-gray-500">
                                <i class="bi bi-image text-4xl mb-2"></i>
                                <p>Nenhuma imagem selecionada</p>
                            </div>
                            <div x-show="blogLogoPreview" class="text-center">
                                <img :src="blogLogoPreview" alt="Preview da logo do blog" class="max-h-20 mx-auto">
                            </div>
                        </div>
                        
                        @if(isset($settings['blog_logo_path']) && $settings['blog_logo_path'])
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Logo atual:</p>
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-800 text-center">
                                    <img src="{{ url('storage/' . $settings['blog_logo_path']) }}" 
                                         alt="Logo atual do blog" 
                                         class="max-h-20 mx-auto">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Logo do Rodapé -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center mb-6">
                    <i class="bi bi-layout-text-window-reverse text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Logo do Rodapé</h3>
                    <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Novo</span>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="footer_logo" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload da Logo do Rodapé
                        </label>
                        
                        <!-- Opções de seleção -->
                        <div class="mb-4 flex space-x-3">
                            <button type="button" 
                                    onclick="openFileManagerlogoFooterFileManager()"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="bi bi-folder mr-2"></i>Selecionar do Gerenciador
                            </button>
                            <button type="button" 
                                    onclick="document.getElementById('footer_logo').click()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="bi bi-upload mr-2"></i>Upload Novo
                            </button>
                        </div>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="bi bi-cloud-upload text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="footer_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Fazer upload</span>
                                    <input type="file" 
                                           id="footer_logo" 
                                           name="footer_logo" 
                                           accept="image/*"
                                           class="sr-only"
                                           @change="previewFooterLogo($event)">
                                </label>
                            </div>
                        </div>
                        @error('footer_logo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <div class="border border-gray-200 rounded-lg p-4 py-8 bg-gray-800">
                            <div x-show="!footerLogoPreview" class="text-center text-gray-400">
                                <i class="bi bi-image text-4xl mb-2"></i>
                                <p>Nenhuma imagem selecionada</p>
                            </div>
                            <div x-show="footerLogoPreview" class="text-center">
                                <img :src="footerLogoPreview" alt="Preview da logo do rodapé" class="max-h-20 mx-auto">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="bi bi-info-circle mr-1"></i>
                            Esta logo será exibida no rodapé do site público
                        </p>
                    </div>
                </div>
            </div>

            <!-- Favicon -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center mb-6">
                    <i class="bi bi-browser-chrome text-primary text-xl mr-3"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Favicon</h3>
                    <span class="ml-2 px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">Ícone do Site</span>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                            Upload do Favicon
                        </label>
                        
                        <!-- Opções de seleção -->
                        <div class="mb-4 flex space-x-3">
                            <button type="button" 
                                    onclick="openFileManagerfaviconFileManager()"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="bi bi-folder mr-2"></i>Selecionar do Gerenciador
                            </button>
                            <button type="button" 
                                    onclick="document.getElementById('favicon').click()"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                                <i class="bi bi-upload mr-2"></i>Upload Novo
                            </button>
                        </div>
                        
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <i class="bi bi-cloud-upload text-gray-400 text-4xl"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="favicon" class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary">
                                    <span>Fazer upload</span>
                                    <input type="file" 
                                           id="favicon" 
                                           name="favicon" 
                                           accept="image/x-icon,image/png,image/svg+xml"
                                           class="sr-only"
                                           @change="previewFavicon($event)">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">ICO, PNG, SVG (recomendado 32x32 ou 64x64)</p>
                        </div>
                        @error('favicon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <div class="border border-gray-200 rounded-lg p-4 py-8 bg-gray-100">
                            <div x-show="!faviconPreview" class="text-center text-gray-400">
                                <i class="bi bi-browser-chrome text-4xl mb-2"></i>
                                <p>Nenhum favicon selecionado</p>
                            </div>
                            <div x-show="faviconPreview" class="text-center">
                                <img :src="faviconPreview" alt="Preview do favicon" class="max-h-16 mx-auto border border-gray-300 rounded">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="bi bi-info-circle mr-1"></i>
                            O favicon aparece na aba do navegador ao lado do título da página
                        </p>
                    </div>
                </div>
            </div>

            <!-- Configurações de cores -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-4">Cores do Site</h4>
                
                <!-- Paletas Pré-definidas -->
                <div class="mb-6">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Paletas Pré-definidas</h5>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        <!-- Paleta Vermelha -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#DC2626', '#FEF2F2', '#F59E0B')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #DC2626;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #FEF2F2;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #F59E0B;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Vermelha</p>
                        </div>
                        
                        <!-- Paleta Azul -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#2563EB', '#EFF6FF', '#3B82F6')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #2563EB;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #EFF6FF;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #3B82F6;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Azul</p>
                        </div>
                        
                        <!-- Paleta Verde -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#059669', '#ECFDF5', '#10B981')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #059669;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #ECFDF5;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #10B981;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Verde</p>
                        </div>
                        
                        <!-- Paleta Roxa -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#7C3AED', '#F3E8FF', '#8B5CF6')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #7C3AED;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #F3E8FF;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #8B5CF6;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Roxa</p>
                        </div>
                        
                        <!-- Paleta Laranja -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#EA580C', '#FFF7ED', '#F97316')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #EA580C;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #FFF7ED;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #F97316;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Laranja</p>
                        </div>
                        
                        <!-- Paleta Rosa -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#DB2777', '#FDF2F8', '#EC4899')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #DB2777;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #FDF2F8;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #EC4899;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Rosa</p>
                        </div>
                        
                        <!-- Paleta Cinza -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#374151', '#F9FAFB', '#6B7280')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #374151;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #F9FAFB;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #6B7280;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Cinza</p>
                        </div>
                        
                        <!-- Paleta Teal -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#0D9488', '#F0FDFA', '#14B8A6')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #0D9488;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #F0FDFA;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #14B8A6;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Teal</p>
                        </div>
                        
                        <!-- Paleta Indigo -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#4F46E5', '#EEF2FF', '#6366F1')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #4F46E5;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #EEF2FF;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #6366F1;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Indigo</p>
                        </div>
                        
                        <!-- Paleta Amarela -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#D97706', '#FFFBEB', '#F59E0B')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #D97706;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #FFFBEB;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #F59E0B;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Amarela</p>
                        </div>
                        
                        <!-- Paleta Ciano -->
                        <div class="border rounded-lg p-3 cursor-pointer hover:shadow-md transition-shadow" onclick="applyColorPalette('#0891B2', '#F0F9FF', '#06B6D4')">
                            <div class="flex space-x-1 mb-2">
                                <div class="w-4 h-4 rounded" style="background-color: #0891B2;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #F0F9FF;"></div>
                                <div class="w-4 h-4 rounded" style="background-color: #06B6D4;"></div>
                            </div>
                            <p class="text-xs text-gray-600">Ciano</p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                            Cor Primária
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   id="primary_color" 
                                   name="primary_color" 
                                   value="{{ old('primary_color', $settings['primary_color'] ?? '#EE0000') }}"
                                   class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                            <input type="text" 
                                   value="{{ old('primary_color', $settings['primary_color'] ?? '#EE0000') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   readonly>
                        </div>
                    </div>

                    <div>
                        <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                            Cor Secundária
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   id="secondary_color" 
                                   name="secondary_color" 
                                   value="{{ old('secondary_color', $settings['secondary_color'] ?? '#f8f9fa') }}"
                                   class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                            <input type="text" 
                                   value="{{ old('secondary_color', $settings['secondary_color'] ?? '#f8f9fa') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   readonly>
                        </div>
                    </div>

                    <div>
                        <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">
                            Cor de Destaque
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   id="accent_color" 
                                   name="accent_color" 
                                   value="{{ old('accent_color', $settings['accent_color'] ?? '#ffc107') }}"
                                   class="w-12 h-10 border border-gray-300 rounded-lg cursor-pointer">
                            <input type="text" 
                                   value="{{ old('accent_color', $settings['accent_color'] ?? '#ffc107') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                   readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Redes Sociais -->
        <div class="bg-white rounded-lg shadow p-6 mt-4">
            <div class="flex items-center mb-6">
                <i class="bi bi-share text-primary text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">Redes Sociais</h3>
            </div>
            
            <p class="text-sm text-gray-600 mb-6">
                Configure suas redes sociais. Os ícones aparecerão automaticamente no rodapé do site.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Facebook -->
                <div>
                    <label for="social_facebook" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="bi bi-facebook text-blue-600 mr-2"></i>
                        Facebook
                    </label>
                    <input type="url" 
                           id="social_facebook" 
                           name="social_facebook" 
                           value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}"
                           placeholder="https://facebook.com/seuperfil"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <!-- Instagram -->
                <div>
                    <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="bi bi-instagram text-pink-600 mr-2"></i>
                        Instagram
                    </label>
                    <input type="url" 
                           id="social_instagram" 
                           name="social_instagram" 
                           value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}"
                           placeholder="https://instagram.com/seuperfil"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <!-- Twitter/X -->
                <div>
                    <label for="social_twitter" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="bi bi-twitter-x text-gray-900 mr-2"></i>
                        Twitter (X)
                    </label>
                    <input type="url" 
                           id="social_twitter" 
                           name="social_twitter" 
                           value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}"
                           placeholder="https://twitter.com/seuperfil"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <!-- LinkedIn -->
                <div>
                    <label for="social_linkedin" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="bi bi-linkedin text-blue-700 mr-2"></i>
                        LinkedIn
                    </label>
                    <input type="url" 
                           id="social_linkedin" 
                           name="social_linkedin" 
                           value="{{ old('social_linkedin', $settings['social_linkedin'] ?? '') }}"
                           placeholder="https://linkedin.com/in/seuperfil"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <!-- YouTube -->
                <div>
                    <label for="social_youtube" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="bi bi-youtube text-red-600 mr-2"></i>
                        YouTube
                    </label>
                    <input type="url" 
                           id="social_youtube" 
                           name="social_youtube" 
                           value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}"
                           placeholder="https://youtube.com/@seucanal"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <!-- TikTok -->
                <div>
                    <label for="social_tiktok" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="bi bi-tiktok text-black mr-2"></i>
                        TikTok
                    </label>
                    <input type="url" 
                           id="social_tiktok" 
                           name="social_tiktok" 
                           value="{{ old('social_tiktok', $settings['social_tiktok'] ?? '') }}"
                           placeholder="https://tiktok.com/@seuperfil"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <!-- Pinterest -->
                <div>
                    <label for="social_pinterest" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="bi bi-pinterest text-red-700 mr-2"></i>
                        Pinterest
                    </label>
                    <input type="url" 
                           id="social_pinterest" 
                           name="social_pinterest" 
                           value="{{ old('social_pinterest', $settings['social_pinterest'] ?? '') }}"
                           placeholder="https://pinterest.com/seuperfil"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="flex justify-end space-x-4 mt-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-red-700 transition-colors"
                    :disabled="loading">
                <i class="bi bi-check-lg mr-2" x-show="!loading"></i>
                <i class="bi bi-arrow-clockwise animate-spin mr-2" x-show="loading"></i>
                <span x-text="loading ? 'Salvando...' : 'Salvar Configurações'"></span>
            </button>
        </div>
        
    </form>
</div>

<!-- File Manager Modal -->
<div id="fileManagerModal" 
     class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden"
     @click="closeFileManager()">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 h-5/6 mx-4 flex flex-col max-w-6xl"
         @click.stop>
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-folder text-white text-lg"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Selecionar Logo</h3>
                    <p class="text-sm text-gray-600">Escolha uma imagem para usar como logo</p>
                </div>
            </div>
            <button onclick="closeFileManager()" 
                    class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors">
                <i class="bi bi-x text-gray-600 text-lg"></i>
            </button>
        </div>
        
        <!-- File Manager Content -->
        <div class="flex-1 p-6 overflow-hidden">
            <!-- Loading -->
            <div id="fileManagerLoading" class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                        <i class="bi bi-arrow-clockwise animate-spin text-2xl text-blue-600"></i>
                    </div>
                    <p class="text-gray-600 font-medium">Carregando imagens...</p>
                </div>
            </div>
            
            <!-- File Grid -->
            <div id="fileManagerGrid" class="hidden h-full overflow-y-auto">
                <!-- Files will be loaded here -->
            </div>
        </div>
        
        <!-- Footer -->
        <div class="flex justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
            <button onclick="closeFileManager()"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                Cancelar
            </button>
        </div>
    </div>
</div>

<script>
function appearanceForm() {
    return {
        loading: false,
        logoPreview: null,
        sidebarLogoPreview: null,
        siteLogoPreview: null,
        blogLogoPreview: null,
        footerLogoPreview: null,
        faviconPreview: null,

        init() {
            // Função helper para gerar URL correta
            const getImageUrl = (path) => {
                if (!path) return null;
                
                // Se já é uma URL completa
                if (path.startsWith('http')) {
                    return path;
                }
                
                // Se começa com /, é um caminho absoluto
                if (path.startsWith('/')) {
                    return path;
                }
                
                // Se começa com storage/, usar asset
                if (path.startsWith('storage/')) {
                    return '{{ asset("") }}' + path;
                }
                
                // Se começa com images/, usar asset
                if (path.startsWith('images/')) {
                    return '{{ asset("") }}' + path;
                }
                
                // Caminho relativo - assumir que está em public/images/
                return '{{ url("images/") }}/' + path;
            };
            
            // Inicializar preview se houver logo atual
            @if(isset($settings['logo_path']) && $settings['logo_path'])
                this.logoPreview = getImageUrl('{{ $settings["logo_path"] }}');
            @else
                this.logoPreview = null;
            @endif
            
            // Inicializar preview se houver logo do sidebar atual
            @if(isset($settings['sidebar_logo_path']) && $settings['sidebar_logo_path'])
                this.sidebarLogoPreview = getImageUrl('{{ $settings["sidebar_logo_path"] }}');
            @else
                this.sidebarLogoPreview = null;
            @endif
            
            // Inicializar preview se houver logo do site atual (com fallback para logo principal)
            @if(isset($settings['site_logo_path']) && $settings['site_logo_path'])
                this.siteLogoPreview = getImageUrl('{{ $settings["site_logo_path"] }}');
            @elseif(isset($settings['logo_path']) && $settings['logo_path'])
                this.siteLogoPreview = getImageUrl('{{ $settings["logo_path"] }}');
            @else
                this.siteLogoPreview = null;
            @endif
            
            // Inicializar preview se houver logo do rodapé atual
            @if(isset($settings['footer_logo_path']) && $settings['footer_logo_path'])
                this.footerLogoPreview = getImageUrl('{{ $settings["footer_logo_path"] }}');
            @else
                this.footerLogoPreview = null;
            @endif
            
            // Inicializar preview se houver favicon atual
            @if(isset($settings['favicon_path']) && $settings['favicon_path'])
                this.faviconPreview = getImageUrl('{{ $settings["favicon_path"] }}');
            @else
                this.faviconPreview = null;
            @endif

            // Sincronizar inputs de cor
            this.syncColorInputs();
            
            // Carregar arquivos
            this.loadFiles();
        },

        previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.logoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        previewSidebarLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.sidebarLogoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        previewBlogLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.blogLogoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        previewFooterLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.footerLogoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        previewFavicon(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.faviconPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        previewSiteLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.siteLogoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        loadFiles() {
            // Usar dados passados do PHP
            const files = @json($files ?? []);
            this.displayFiles(files);
        },

        displayFiles(items) {
            const grid = document.getElementById('fileManagerGrid');
            const loading = document.getElementById('fileManagerLoading');
            
            if (loading) loading.classList.add('hidden');
            if (grid) grid.classList.remove('hidden');
            
            // Filtrar apenas imagens
            const images = items.filter(item => 
                item.type === 'file' && 
                ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico'].includes(item.extension?.toLowerCase())
            );
            
            grid.innerHTML = images.map(item => `
                <div class="relative group cursor-pointer rounded-xl p-4 transition-all duration-300 h-32 bg-gray-50 border-2 border-gray-200 hover:bg-gray-100 hover:border-gray-300 hover:shadow-lg"
                     onclick="selectImage('${item.url}')">
                    <div class="flex flex-col items-center justify-center h-full">
                        <i class="bi bi-file-earmark-image text-4xl text-green-500 mb-2"></i>
                        <p class="text-xs text-gray-700 text-center truncate w-full" title="${item.name}">
                            ${item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name}
                        </p>
                    </div>
                </div>
            `).join('');
        },

        syncColorInputs() {
            // Sincronizar color picker com input de texto
            document.querySelectorAll('input[type="color"]').forEach(colorInput => {
                const textInput = colorInput.parentElement.nextElementSibling;
                colorInput.addEventListener('input', () => {
                    textInput.value = colorInput.value;
                });
            });
        }
    }
}

// Função para aplicar paletas de cores
function applyColorPalette(primary, secondary, accent) {
    // Aplicar cores nos inputs
    const primaryInput = document.getElementById('primary_color');
    const secondaryInput = document.getElementById('secondary_color');
    const accentInput = document.getElementById('accent_color');
    
    if (primaryInput) {
        primaryInput.value = primary;
        primaryInput.nextElementSibling.value = primary;
    }
    
    if (secondaryInput) {
        secondaryInput.value = secondary;
        secondaryInput.nextElementSibling.value = secondary;
    }
    
    if (accentInput) {
        accentInput.value = accent;
        accentInput.nextElementSibling.value = accent;
    }
    
    // Mostrar feedback visual
    showNotification('Paleta de cores aplicada!', 'success');
}

// Funções do modal
function openFileManager(context = 'main') {
    const modal = document.getElementById('fileManagerModal');
    const loading = document.getElementById('fileManagerLoading');
    const grid = document.getElementById('fileManagerGrid');
    
    // Definir contexto da seleção
    window.currentImageSelector = context;
    
    modal.classList.remove('hidden');
    loading.classList.remove('hidden');
    grid.classList.add('hidden');
    
    // Carregar arquivos
    loadFilesInModal();
}

function closeFileManager() {
    const modal = document.getElementById('fileManagerModal');
    modal.classList.add('hidden');
}

function loadFilesInModal() {
    // Usar dados passados do PHP
    const files = @json($files ?? []);
    
    console.log('Files loaded:', files); // Debug
    
    setTimeout(() => {
        displayFilesInModal(files);
    }, 100); // Reduzir tempo de carregamento
}

function displayFilesInModal(files) {
    const grid = document.getElementById('fileManagerGrid');
    const loading = document.getElementById('fileManagerLoading');
    
    console.log('Displaying files:', files); // Debug
    
    if (loading) loading.classList.add('hidden');
    if (grid) grid.classList.remove('hidden');
    
    // Verificar se o grid existe
    if (!grid) {
        console.error('Grid element not found');
        return;
    }
    
    // Verificar se há arquivos
    if (!files || files.length === 0) {
        grid.innerHTML = `
            <div class="text-center py-12">
                <i class="bi bi-folder-x text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum arquivo encontrado</h3>
                <p class="text-gray-500">Faça upload de algumas imagens para começar.</p>
            </div>
        `;
        return;
    }
    
    // Filtrar apenas imagens
    const images = files.filter(file => 
        file.type === 'file' && 
        file.extension && 
        ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico'].includes(file.extension.toLowerCase())
    );
    
    console.log('Filtered images:', images); // Debug
    
    if (images.length === 0) {
        grid.innerHTML = `
            <div class="text-center py-12">
                <i class="bi bi-image text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma imagem encontrada</h3>
                <p class="text-gray-500">Faça upload de imagens (JPG, PNG, GIF, SVG) para usar como logo.</p>
            </div>
        `;
        return;
    }
    
    // Agrupar por pasta
    const groupedFiles = images.reduce((groups, file) => {
        const folder = file.folder || 'Raiz';
        if (!groups[folder]) {
            groups[folder] = [];
        }
        groups[folder].push(file);
        return groups;
    }, {});
    
    console.log('Grouped files:', groupedFiles); // Debug
    
    // Usar o próprio grid como container
    grid.innerHTML = Object.keys(groupedFiles).map(folder => {
        const folderFiles = groupedFiles[folder];
        return `
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-folder text-yellow-500 mr-2"></i>
                    ${folder}
                    <span class="ml-2 text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">${folderFiles.length}</span>
                </h4>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    ${folderFiles.map(file => `
                        <div class="relative group cursor-pointer rounded-xl p-4 transition-all duration-300 h-32 bg-gray-50 border-2 border-gray-200 hover:bg-gray-100 hover:border-gray-300 hover:shadow-lg"
                             onclick="selectImageFromModal('${file.url}', '${file.name}')">
                            <div class="text-center flex flex-col items-center justify-center h-full">
                                <i class="text-4xl mb-3 ${getFileIcon(file.extension)}"></i>
                                <p class="text-sm font-bold text-gray-900 truncate px-2" title="${file.name}">
                                    ${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}
                                </p>
                                <p class="text-xs text-gray-700 font-semibold truncate px-2">
                                    ${file.extension ? file.extension.toUpperCase() : ''}
                                </p>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }).join('');
}

function selectImage(imageUrl) {
    console.log('selectImage called with:', imageUrl);
    
    // Preencher campo de URL da logo principal
    const urlField = document.getElementById('image_url');
    if (urlField) {
        urlField.value = imageUrl;
        console.log('Main logo URL field updated');
    } else {
        console.log('Main logo URL field not found');
    }
    
    // Atualizar Alpine.js data para logo principal
    updateAlpineData('logoPreview', imageUrl);
    
    // Forçar atualização do preview via Alpine.js
    updateAlpinePreview('logoPreview', imageUrl);
}

function selectSidebarImage(imageUrl) {
    console.log('selectSidebarImage called with:', imageUrl);
    
    // Preencher campo de URL da logo do sidebar
    const urlField = document.getElementById('sidebar_image_url');
    if (urlField) {
        urlField.value = imageUrl;
        console.log('Sidebar logo URL field updated');
    } else {
        console.log('Sidebar logo URL field not found');
    }
    
    // Atualizar Alpine.js data para logo do sidebar
    updateAlpineData('sidebarLogoPreview', imageUrl);
    
    // Forçar atualização do preview via Alpine.js
    updateAlpinePreview('sidebarLogoPreview', imageUrl);
}

function updateAlpineData(property, value) {
    if (window.Alpine) {
        // Tentar encontrar o componente Alpine ativo
        const alpineComponent = document.querySelector('[x-data*="appearanceForm"]');
        if (alpineComponent && alpineComponent._x_dataStack) {
            const alpineData = alpineComponent._x_dataStack[0];
            if (alpineData && alpineData[property] !== undefined) {
                alpineData[property] = value;
                console.log(`Alpine.js ${property} updated to:`, value);
            } else {
                console.log(`Alpine.js property ${property} not found`);
            }
        } else {
            console.log('Alpine.js component not found');
        }
    }
}

function updateAlpinePreview(property, value) {
    // Forçar atualização do preview via Alpine.js
    if (window.Alpine) {
        const alpineComponent = document.querySelector('[x-data*="appearanceForm"]');
        if (alpineComponent && alpineComponent._x_dataStack) {
            const alpineData = alpineComponent._x_dataStack[0];
            if (alpineData && alpineData[property] !== undefined) {
                alpineData[property] = value;
                console.log(`Alpine.js preview ${property} updated to:`, value);
                
                // Disparar evento para forçar re-render
                alpineComponent.dispatchEvent(new CustomEvent('alpine:update'));
            }
        }
    }
}



function selectImageFromModal(imageUrl, fileName) {
    console.log('selectImageFromModal called with:', { imageUrl, fileName });
    console.log('Current selector context:', window.currentImageSelector);
    
    // Verificar qual tipo de seleção está ativo
    const selector = window.currentImageSelector;
    const isSidebarSelection = selector === 'sidebar';
    const isSiteSelection = selector === 'site';

    if (isSidebarSelection) {
        console.log('Processing sidebar logo selection');
        selectSidebarImage(imageUrl);
    } else if (isSiteSelection) {
        console.log('Processing site logo selection');
        // Preencher campo de URL da logo do site
        const urlField = document.getElementById('site_image_url');
        if (urlField) {
            urlField.value = imageUrl;
            console.log('Site logo URL field updated');
        }
        // Atualizar Alpine.js preview para logo do site
        updateAlpineData('siteLogoPreview', imageUrl);
        updateAlpinePreview('siteLogoPreview', imageUrl);
    } else {
        console.log('Processing main logo selection');
        selectImage(imageUrl);
    }
    
    // Fechar modal
    closeFileManager();
    
    // Mostrar feedback
    showNotification(`Imagem "${fileName}" selecionada!`, 'success');
    
    // Limpar seletor
    window.currentImageSelector = null;
}

function getFileIcon(extension) {
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
    return icons[extension?.toLowerCase()] || 'bi bi-file-earmark text-gray-500';
}

// Nota: A função showNotification() está definida globalmente em notification-container.blade.php

</script>

<!-- Componentes File Manager para Logos -->
<x-file-manager-modal 
    modal-id="logoMainFileManager" 
    title="Selecionar Logo do Sidebar" 
    on-select-callback="selectLogoMain" />

<x-file-manager-modal 
    modal-id="logoSiteFileManager" 
    title="Selecionar Logo do Site Público" 
    on-select-callback="selectLogoSite" />

<x-file-manager-modal 
    modal-id="logoSidebarFileManager" 
    title="Selecionar Logo do Sidebar do Cliente" 
    on-select-callback="selectLogoSidebar" />

<x-file-manager-modal 
    modal-id="logoBlogFileManager" 
    title="Selecionar Logo do Blog" 
    on-select-callback="selectLogoBlog" />

<x-file-manager-modal 
    modal-id="logoFooterFileManager" 
    title="Selecionar Logo do Rodapé" 
    on-select-callback="selectLogoFooter" />

<x-file-manager-modal 
    modal-id="faviconFileManager" 
    title="Selecionar Favicon" 
    on-select-callback="selectFavicon" />

<script>
// Função helper global para gerar URL correta de imagem
function getImageUrl(path) {
    if (!path) return '';
    
    // Se já é uma URL completa
    if (path.startsWith('http')) {
        return path;
    }
    
    // Se começa com /, é um caminho absoluto
    if (path.startsWith('/')) {
        return path;
    }
    
    // Se começa com storage/ ou images/
    if (path.startsWith('storage/') || path.startsWith('images/')) {
        return '{{ asset("") }}' + path;
    }
    
    // Caminho relativo - assumir que está em public/images/
    return '{{ url("images/") }}/' + path;
}

// Callbacks para seleção de logos
function selectLogoMain(imagePath) {
    const imageUrl = getImageUrl(imagePath);
    
    // Atualizar campo hidden
    document.getElementById('image_url').value = imagePath;
    
    // Atualizar preview no Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="appearanceForm()"]'));
    if (component) {
        component.logoPreview = imageUrl;
    }
    
    closeFileManagerlogoMainFileManager();
}

function selectLogoSite(imagePath) {
    const imageUrl = getImageUrl(imagePath);
    
    // Atualizar campo hidden
    document.getElementById('site_image_url').value = imagePath;
    
    // Atualizar preview no Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="appearanceForm()"]'));
    if (component) {
        component.siteLogoPreview = imageUrl;
    }
    
    closeFileManagerlogoSiteFileManager();
}

function selectLogoSidebar(imagePath) {
    const imageUrl = getImageUrl(imagePath);
    
    // Atualizar campo hidden
    document.getElementById('sidebar_image_url').value = imagePath;
    
    // Atualizar preview no Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="appearanceForm()"]'));
    if (component) {
        component.sidebarLogoPreview = imageUrl;
    }
    
    closeFileManagerlogoSidebarFileManager();
}

function selectLogoBlog(imagePath) {
    const imageUrl = getImageUrl(imagePath);
    
    // Atualizar campo hidden
    document.getElementById('blog_image_url').value = imagePath;
    
    // Atualizar preview no Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="appearanceForm()"]'));
    if (component) {
        component.blogLogoPreview = imageUrl;
    }
    
    closeFileManagerlogoBlogFileManager();
}

function selectLogoFooter(imagePath) {
    const imageUrl = getImageUrl(imagePath);
    
    // Atualizar campo hidden
    document.getElementById('footer_image_url').value = imagePath;
    
    // Atualizar preview no Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="appearanceForm()"]'));
    if (component) {
        component.footerLogoPreview = imageUrl;
    }
    
    closeFileManagerlogoFooterFileManager();
}

function selectFavicon(imagePath) {
    const imageUrl = getImageUrl(imagePath);
    
    // Atualizar campo hidden
    document.getElementById('favicon_image_url').value = imagePath;
    
    // Atualizar preview no Alpine.js
    const component = Alpine.$data(document.querySelector('[x-data*="appearanceForm()"]'));
    if (component) {
        component.faviconPreview = imageUrl;
    }
    
    closeFileManagerfaviconFileManager();
}
</script>

@endsection