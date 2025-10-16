<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login Admin - <?php echo e(\App\Models\Setting::get('site_name', 'Laser Link')); ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '<?php echo e(\App\Models\Setting::get("primary_color", "#EE0000")); ?>',
                        secondary: '<?php echo e(\App\Models\Setting::get("secondary_color", "#f8f9fa")); ?>',
                        accent: '<?php echo e(\App\Models\Setting::get("accent_color", "#ffc107")); ?>',
                    }
                }
            }
        }
    </script>
    
    <!-- CSS personalizado para cores dinâmicas -->
    <style>
        :root {
            --primary-color: <?php echo e(\App\Models\Setting::get('primary_color', '#EE0000')); ?>;
            --secondary-color: <?php echo e(\App\Models\Setting::get('secondary_color', '#f8f9fa')); ?>;
            --accent-color: <?php echo e(\App\Models\Setting::get('accent_color', '#ffc107')); ?>;
        }
        
        .bg-primary { background-color: var(--primary-color) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }
        .hover\:bg-primary:hover { background-color: var(--primary-color) !important; }
        .focus\:ring-primary:focus { --tw-ring-color: var(--primary-color) !important; }
        
        .bg-secondary { background-color: var(--secondary-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }
        
        .bg-accent { background-color: var(--accent-color) !important; }
        .text-accent { color: var(--accent-color) !important; }
    </style>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo e Header -->
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <?php
                    // Preferir logo do site público; fallback para logo do sidebar
                    $siteLogoPath = \App\Models\Setting::get('site_logo_path');
                    $sidebarLogoPath = \App\Models\Setting::get('logo_path');
                    $selectedLogoPath = $siteLogoPath ?: $sidebarLogoPath;
                    $siteName = \App\Models\Setting::get('site_name', 'Laser Link');
                ?>
                
                <?php if($selectedLogoPath): ?>
                    <!-- Logo com imagem - título oculto -->
                    <img src="<?php echo e(asset('images/' . $selectedLogoPath)); ?>?v=<?php echo e(time()); ?>" 
                         alt="<?php echo e($siteName); ?>" 
                         class="h-16 w-auto object-contain"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="h-16 w-16 bg-primary rounded-lg flex items-center justify-center" style="display: none;">
                        <i class="bi bi-lightning text-white text-3xl"></i>
                    </div>
                <?php else: ?>
                    <!-- Logo sem imagem - com título -->
                    <div class="h-16 w-16 bg-primary rounded-lg flex items-center justify-center">
                        <i class="bi bi-lightning text-white text-3xl"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulário de Login -->
        <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
            <form class="space-y-6" method="POST" action="<?php echo e(route('admin.login.post')); ?>">
                <?php echo csrf_field(); ?>

                <!-- Error Messages -->
                <?php if($errors->any()): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="bi bi-exclamation-circle mr-2"></i>
                            <div>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <p><?php echo e($error); ?></p>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Success Messages -->
                <?php if(session('success')): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="bi bi-check-circle mr-2"></i>
                            <?php echo e(session('success')); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-mail
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-envelope text-gray-400"></i>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="seu@email.com"
                               value="<?php echo e(old('email')); ?>">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Senha
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-gray-400"></i>
                        </div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password" 
                               required 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Sua senha">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" 
                               name="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Lembrar de mim
                        </label>
                    </div>

                    <?php if(Route::has('password.request')): ?>
                        <div class="text-sm">
                            <a href="<?php echo e(route('password.request')); ?>" 
                               class="font-medium text-primary hover:text-red-700">
                                Esqueceu a senha?
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="bi bi-box-arrow-in-right text-white"></i>
                        </span>
                        Entrar
                    </button>
                </div>

                <!-- Register Link -->
                <?php if(\App\Models\Setting::get('admin_register_enabled', false)): ?>
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Não tem uma conta?
                            <a href="<?php echo e(route('admin.register')); ?>" 
                               class="font-medium text-primary hover:text-red-700">
                                Criar conta
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center">
            <p class="text-xs text-gray-500">
                © <?php echo e(date('Y')); ?> <?php echo e(\App\Models\Setting::get('site_name', 'Laser Link')); ?>. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>