@extends('admin.layout')

@section('title', 'Configurações')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Configurações</li>
                    </ol>
                </div>
                <h4 class="page-title">Configurações do Sistema</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Configurações Gerais -->
        <div class="col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle text-primary">
                                <i class="ri-settings-3-line font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">Configurações Gerais</h5>
                            <p class="text-muted mb-0">Informações básicas do site</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.settings.general') }}" class="btn btn-primary btn-sm">
                            <i class="ri-arrow-right-line me-1"></i> Configurar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- WhatsApp -->
        <div class="col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle text-success">
                                <i class="ri-whatsapp-line font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">WhatsApp</h5>
                            <p class="text-muted mb-0">Configurações de contato</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.settings.whatsapp') }}" class="btn btn-success btn-sm">
                            <i class="ri-arrow-right-line me-1"></i> Configurar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aparência -->
        <div class="col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle text-warning">
                                <i class="ri-palette-line font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">Aparência</h5>
                            <p class="text-muted mb-0">Cores, logo e visual</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.settings.appearance') }}" class="btn btn-warning btn-sm">
                            <i class="ri-arrow-right-line me-1"></i> Configurar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-info-subtle text-info">
                                <i class="ri-search-line font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">SEO</h5>
                            <p class="text-muted mb-0">Otimização para buscadores</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.settings.seo') }}" class="btn btn-info btn-sm">
                            <i class="ri-arrow-right-line me-1"></i> Configurar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gemini AI -->
        <div class="col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-purple-subtle text-purple">
                                <i class="ri-robot-line font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">Gemini AI</h5>
                            <p class="text-muted mb-0">Configurações de IA</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.settings.gemini') }}" class="btn btn-purple btn-sm">
                            <i class="ri-arrow-right-line me-1"></i> Configurar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo das Configurações -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Resumo das Configurações</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Configurações Ativas</h6>
                            <ul class="list-unstyled">
                                <li><i class="ri-check-line text-success me-2"></i> Site: {{ $settings['site_name'] ?? 'Laser Link' }}</li>
                                <li><i class="ri-check-line text-success me-2"></i> Email: {{ $settings['contact_email'] ?? 'contato@laserlink.com.br' }}</li>
                                <li><i class="ri-check-line text-success me-2"></i> WhatsApp: {{ $settings['whatsapp_number'] ?? 'Não configurado' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Status do Sistema</h6>
                            <ul class="list-unstyled">
                                <li><i class="ri-check-line text-success me-2"></i> Sistema operacional</li>
                                <li><i class="ri-check-line text-success me-2"></i> Banco de dados conectado</li>
                                <li><i class="ri-check-line text-success me-2"></i> Cache ativo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 2.5rem;
    height: 2.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-purple-subtle {
    background-color: rgba(102, 16, 242, 0.1);
}

.text-purple {
    color: #6610f2 !important;
}

.btn-purple {
    background-color: #6610f2;
    border-color: #6610f2;
    color: #fff;
}

.btn-purple:hover {
    background-color: #5a0dd8;
    border-color: #5a0dd8;
    color: #fff;
}
</style>
@endpush
