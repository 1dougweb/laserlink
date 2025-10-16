@extends('layouts.store')

@section('title', 'Produtos Personalizáveis')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Produtos Personalizáveis</h1>
            <p class="lead mb-5">Personalize seu produto com materiais, acabamentos e dimensões sob medida.</p>
        </div>
    </div>

    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if($product->featured_image)
                            <img src="{{ url('images/' . $product->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}"
                                 style="height: 250px; object-fit: cover;"
                                 onerror="this.src='{{ url('images/general/callback-image.svg') }}'; this.classList.add('object-contain', 'bg-gray-100');">
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="h5 text-primary mb-0">A partir de R$ {{ number_format($product->base_price, 2, ',', '.') }}</span>
                                    <span class="badge bg-success">Personalizável</span>
                                </div>
                                
                                <a href="{{ route('store.product-customization', $product->slug) }}" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-cog me-2"></i>Personalizar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-cog fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Nenhum produto personalizável disponível</h4>
                    <p class="text-muted">Em breve teremos produtos incríveis para personalizar!</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-5px);
}

.badge {
    font-size: 0.8rem;
}
</style>
@endpush
