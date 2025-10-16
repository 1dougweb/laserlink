<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Laser Link'); ?> - <?php echo e(\App\Models\Setting::get('site_name', 'Laser Link')); ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
    
    <!-- Alpine is bootstrapped via resources/js/app.js -->
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

        <!-- Formulário -->
        <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
            <?php echo e($slot); ?>

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
<?php /**PATH C:\xampp\htdocs\resources\views/layouts/guest.blade.php ENDPATH**/ ?>