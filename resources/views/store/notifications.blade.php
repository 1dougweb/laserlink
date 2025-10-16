@extends('layouts.dashboard')

@section('title', 'Minhas Notificações')

@section('page-title', 'Minhas Notificações')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="p-6">
            @if($notifications->isEmpty())
                <div class="text-center py-12">
                    <i class="bi bi-bell text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500">Nenhuma notificação no momento</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($notifications as $notification)
                        <div class="flex items-start gap-6 p-4 {{ $notification->read_at ? 'opacity-75' : 'bg-gray-50' }} rounded-lg transition-all hover:bg-gray-50">
                            <div class="flex-shrink-0">
                                <i class="{{ $notification->icon ?? 'bi bi-info-circle' }} text-3xl {{ $notification->read_at ? 'text-gray-400' : 'text-primary' }}"></i>
                            </div>
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $notification->title }}</h3>
                                    <time datetime="{{ $notification->created_at }}" class="text-sm text-gray-500">
                                        {{ $notification->created_at->format('d/m/Y H:i') }}
                                    </time>
                                </div>
                                <p class="mt-2 text-gray-600">{{ $notification->message }}</p>
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="inline-block mt-3 text-sm font-medium text-primary hover:text-primary-dark">
                                        Ver detalhes
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection