@extends('layouts.dashboard')

@section('title', 'Meu Perfil - Laser Link')
@section('page-title', 'Meu Perfil')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('status') === 'profile-updated')
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="bi bi-check-circle mr-2"></i>
                Perfil atualizado com sucesso!
            </div>
        </div>
    @endif

    @if(session('status') === 'password-updated')
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <div class="flex items-center">
                <i class="bi bi-check-circle mr-2"></i>
                Senha atualizada com sucesso!
            </div>
        </div>
    @endif

    <!-- Profile Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bi bi-person mr-2 text-primary"></i>
                Informações do Perfil
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                Atualize suas informações de perfil e endereço de e-mail.
            </p>
        </div>
        <div class="px-6 py-4">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Password Update -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <i class="bi bi-shield-lock mr-2 text-primary"></i>
                Atualizar Senha
            </h3>
            <p class="mt-1 text-sm text-gray-600">
                Certifique-se de que sua conta está usando uma senha longa e aleatória para manter a segurança.
            </p>
        </div>
        <div class="px-6 py-4">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white shadow rounded-lg border-red-200">
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <h3 class="text-lg font-medium text-red-900 flex items-center">
                <i class="bi bi-exclamation-triangle mr-2 text-red-600"></i>
                Excluir Conta
            </h3>
            <p class="mt-1 text-sm text-red-600">
                Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. 
                Antes de excluir sua conta, baixe todos os dados ou informações que deseja reter.
            </p>
        </div>
        <div class="px-6 py-4">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
