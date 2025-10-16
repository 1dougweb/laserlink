

<?php $__env->startSection('title', ($page->meta_title ?? $page->title) . ' - ' . config('app.name')); ?>

<?php $__env->startSection('meta_description', $page->meta_description ?? Str::limit(strip_tags($page->content), 160)); ?>

<?php $__env->startSection('content'); ?>

<!-- Breadcrumb -->
<div class="bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <nav class="text-sm text-gray-600">
            <a href="<?php echo e(url('/')); ?>" class="hover:text-primary transition-colors">
                <i class="bi bi-house-door mr-1"></i>Início
            </a>
            <span class="mx-2">/</span>
            <span class="text-gray-900"><?php echo e($page->title); ?></span>
        </nav>
    </div>
</div>

<!-- Page Content -->
<div class="bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 md:mb-10">
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4 leading-tight"><?php echo e($page->title); ?></h1>
            <div class="flex items-center text-sm text-gray-600">
                <i class="bi bi-calendar3 mr-2"></i>
                <span>Última atualização: <?php echo e($page->updated_at->format('d \d\e F \d\e Y')); ?></span>
            </div>
        </div>
        
        <!-- Page Content Card -->
        <article class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="page-content p-6 sm:p-8 md:p-12 lg:p-16">
                <?php echo $page->content; ?>

            </div>
        </article>
        
        <!-- Back Button -->
        <div class="mt-10 text-center">
            <a href="<?php echo e(url('/')); ?>" 
               class="inline-flex items-center px-8 py-3.5 bg-white border-2 border-gray-300 hover:border-primary hover:bg-gray-50 text-gray-700 hover:text-primary rounded-lg transition-all font-semibold shadow-sm">
                <i class="bi bi-arrow-left mr-2 text-lg"></i>
                Voltar para o Início
            </a>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Estilos aprimorados para o conteúdo da página */
    .page-content {
        line-height: 1.9;
        color: #1f2937;
        font-size: 1.125rem;
        max-width: 65ch;
        margin: 0 auto;
    }
    
    @media (min-width: 1024px) {
        .page-content {
            max-width: 75ch;
        }
    }
    
    /* Títulos e Headings - Melhorados */
    .page-content h1 {
        font-size: 2.75rem;
        font-weight: 800;
        margin-top: 3.5rem;
        margin-bottom: 1.75rem;
        color: #111827;
        line-height: 1.15;
        letter-spacing: -0.025em;
    }
    
    .page-content h1:first-child {
        margin-top: 0;
    }
    
    .page-content h2 {
        font-size: 2.25rem;
        font-weight: 700;
        margin-top: 4rem;
        margin-bottom: 1.5rem;
        color: #111827;
        padding-bottom: 1rem;
        border-bottom: 4px solid #dc2626;
        line-height: 1.25;
        letter-spacing: -0.02em;
        position: relative;
    }
    
    .page-content h2::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 0;
        width: 60px;
        height: 4px;
        background: #991b1b;
    }
    
    .page-content h3 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-top: 3rem;
        margin-bottom: 1.25rem;
        color: #1f2937;
        line-height: 1.35;
        position: relative;
        padding-left: 1.25rem;
    }
    
    .page-content h3::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.25rem;
        width: 5px;
        height: 2rem;
        background: linear-gradient(180deg, #dc2626 0%, #991b1b 100%);
        border-radius: 3px;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
    }
    
    .page-content h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1.75rem;
        margin-bottom: 0.875rem;
        color: #4b5563;
        line-height: 1.5;
    }
    
    /* Parágrafos e texto - Melhorados */
    .page-content p {
        margin-bottom: 1.75rem;
        color: #374151;
        line-height: 1.9;
        font-size: 1.125rem;
    }
    
    .page-content p:last-child {
        margin-bottom: 0;
    }
    
    .page-content p:first-of-type {
        font-size: 1.1875rem;
        color: #1f2937;
    }
    
    /* Listas - Melhoradas */
    .page-content ul, 
    .page-content ol {
        margin-bottom: 2rem;
        padding-left: 0;
        margin-left: 0.5rem;
    }
    
    .page-content ul {
        list-style-type: none;
    }
    
    .page-content ul li {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 1rem;
    }
    
    .page-content ul li::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0.7rem;
        width: 10px;
        height: 10px;
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);
    }
    
    .page-content ol {
        list-style-type: none;
        counter-reset: item;
    }
    
    .page-content ol li {
        position: relative;
        padding-left: 2.5rem;
        counter-increment: item;
        margin-bottom: 1rem;
    }
    
    .page-content ol li::before {
        content: counter(item) ".";
        position: absolute;
        left: 0;
        top: 0;
        font-weight: 800;
        color: #dc2626;
        font-size: 1.25rem;
        min-width: 2rem;
    }
    
    .page-content li {
        color: #374151;
        line-height: 1.85;
        font-size: 1.0625rem;
    }
    
    .page-content li:last-child {
        margin-bottom: 0;
    }
    
    /* Listas aninhadas */
    .page-content ul ul,
    .page-content ol ol,
    .page-content ul ol,
    .page-content ol ul {
        margin-top: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .page-content ul ul li::before {
        width: 6px;
        height: 6px;
        background-color: #9ca3af;
    }
    
    /* Links - Melhorados */
    .page-content a {
        color: #dc2626;
        text-decoration: none;
        font-weight: 600;
        border-bottom: 2px solid rgba(220, 38, 38, 0.3);
        transition: all 0.3s ease;
        position: relative;
    }
    
    .page-content a:hover {
        color: #991b1b;
        border-bottom-color: #dc2626;
        background-color: rgba(220, 38, 38, 0.05);
        padding: 0 0.125rem;
    }
    
    /* Ênfases - Melhoradas */
    .page-content strong {
        font-weight: 800;
        color: #111827;
        background: linear-gradient(180deg, transparent 60%, rgba(220, 38, 38, 0.1) 60%);
        padding: 0 0.125rem;
    }
    
    .page-content em {
        font-style: italic;
        color: #4b5563;
    }
    
    /* Imagens */
    .page-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.75rem;
        margin: 2rem auto;
        display: block;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #e5e7eb;
    }
    
    /* Blockquotes - Melhorados */
    .page-content blockquote {
        border-left: 6px solid #dc2626;
        background: linear-gradient(135deg, #fef2f2 0%, #fef8f8 50%, #ffffff 100%);
        padding: 2rem 2.5rem;
        margin: 3rem 0;
        font-style: italic;
        color: #1f2937;
        border-radius: 0 1rem 1rem 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        position: relative;
        font-size: 1.1875rem;
        line-height: 1.8;
    }
    
    .page-content blockquote::before {
        content: '"';
        font-size: 5rem;
        color: rgba(220, 38, 38, 0.15);
        position: absolute;
        top: -0.75rem;
        left: 1.5rem;
        font-family: Georgia, serif;
        line-height: 1;
        font-weight: 700;
    }
    
    .page-content blockquote p {
        margin-bottom: 0;
        position: relative;
        z-index: 1;
    }
    
    /* Code e Pre */
    .page-content pre {
        background-color: #1f2937;
        color: #f9fafb;
        padding: 1.5rem;
        border-radius: 0.75rem;
        overflow-x: auto;
        margin: 2rem 0;
        border: 1px solid #374151;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .page-content code {
        background-color: #fef2f2;
        color: #dc2626;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.9em;
        font-family: 'Monaco', 'Courier New', Courier, monospace;
        font-weight: 500;
        border: 1px solid #fecaca;
    }
    
    .page-content pre code {
        background-color: transparent;
        padding: 0;
        color: inherit;
        border: none;
        font-size: 0.875rem;
    }
    
    /* Tabelas - Melhoradas */
    .page-content table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 3rem 0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 2px solid #e5e7eb;
    }
    
    .page-content thead {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
    }
    
    .page-content th {
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-weight: 700;
        color: #ffffff;
        text-transform: uppercase;
        font-size: 0.8125rem;
        letter-spacing: 0.08em;
        border: none;
    }
    
    .page-content td {
        border-top: 1px solid #e5e7eb;
        padding: 1.25rem 1.5rem;
        color: #374151;
        font-size: 1rem;
    }
    
    .page-content tbody tr {
        background-color: #ffffff;
        transition: all 0.3s ease;
    }
    
    .page-content tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }
    
    .page-content tbody tr:hover {
        background-color: #fef2f2;
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.1);
    }
    
    /* Horizontal Rule */
    .page-content hr {
        margin: 3rem 0;
        border: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, #dc2626 50%, transparent 100%);
    }
    
    /* Elementos especiais */
    .page-content .bg-blue-50,
    .page-content .bg-green-50,
    .page-content .bg-yellow-50,
    .page-content .bg-red-50 {
        padding: 1.5rem;
        border-radius: 0.75rem;
        margin: 1.5rem 0;
        border-left: 4px solid;
    }
    
    .page-content .bg-blue-50 {
        background-color: #eff6ff;
        border-color: #3b82f6;
    }
    
    .page-content .bg-green-50 {
        background-color: #f0fdf4;
        border-color: #22c55e;
    }
    
    .page-content .bg-yellow-50 {
        background-color: #fefce8;
        border-color: #eab308;
    }
    
    .page-content .bg-red-50 {
        background-color: #fef2f2;
        border-color: #dc2626;
    }
    
    /* Botões e CTAs dentro do conteúdo */
    .page-content .btn,
    .page-content button:not([class*="ql-"]) {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        color: #ffffff;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.3);
    }
    
    .page-content .btn:hover,
    .page-content button:not([class*="ql-"]):hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.4);
        color: #ffffff;
        border-bottom-color: transparent;
    }
    
    /* Responsividade aprimorada */
    @media (max-width: 1024px) {
        .page-content {
            font-size: 1.0625rem;
        }
    }
    
    @media (max-width: 768px) {
        .page-content {
            font-size: 1rem;
            max-width: 100%;
        }
        
        .page-content h1 {
            font-size: 2.25rem;
            margin-top: 2rem;
            margin-bottom: 1.25rem;
        }
        
        .page-content h2 {
            font-size: 1.875rem;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .page-content h3 {
            font-size: 1.5rem;
            margin-top: 2rem;
            margin-bottom: 0.875rem;
        }
        
        .page-content h4 {
            font-size: 1.1875rem;
            margin-top: 1.5rem;
        }
        
        .page-content p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .page-content p:first-of-type {
            font-size: 1.0625rem;
        }
        
        .page-content li {
            font-size: 1rem;
        }
        
        .page-content table {
            font-size: 0.875rem;
            display: block;
            overflow-x: auto;
            white-space: nowrap;
            margin: 2rem 0;
        }
        
        .page-content th,
        .page-content td {
            padding: 0.875rem 1rem;
        }
        
        .page-content blockquote {
            padding: 1.5rem 1.75rem;
            font-size: 1.0625rem;
            margin: 2rem 0;
        }
        
        .page-content blockquote::before {
            font-size: 3.5rem;
        }
        
        .page-content ul,
        .page-content ol {
            margin-left: 0;
        }
        
        .page-content ul li {
            padding-left: 1.75rem;
        }
        
        .page-content ol li {
            padding-left: 2rem;
        }
        
        .page-content code {
            font-size: 0.875rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\resources\views/pages/show.blade.php ENDPATH**/ ?>