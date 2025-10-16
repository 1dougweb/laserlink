@extends('admin.layout')

@section('title', 'Enviar Promoção')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Enviar Promoção</h1>
            <p class="text-gray-600 mt-1">Envie mensagens promocionais para seus clientes</p>
        </div>
        <a href="{{ route('admin.whatsapp.notifications.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all">
            <i class="bi bi-arrow-left mr-2"></i> Voltar
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.whatsapp.notifications.store-promotion') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="instance_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Instância *
                    </label>
                    <select name="instance_id" id="instance_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('instance_id') border-red-500 @enderror">
                        <option value="">Selecione uma instância</option>
                        @foreach($instances as $instance)
                            <option value="{{ $instance->id }}" {{ old('instance_id') == $instance->id ? 'selected' : '' }}>
                                {{ $instance->name }} ({{ $instance->purpose_label }})
                            </option>
                        @endforeach
                    </select>
                    @error('instance_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-2">
                        Público Alvo *
                    </label>
                    <select name="target_audience" id="target_audience" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('target_audience') border-red-500 @enderror">
                        <option value="">Selecione o público</option>
                        <option value="all" {{ old('target_audience') == 'all' ? 'selected' : '' }}>Todos os clientes</option>
                        <option value="with_orders" {{ old('target_audience') == 'with_orders' ? 'selected' : '' }}>Clientes com pedidos</option>
                        <option value="without_orders" {{ old('target_audience') == 'without_orders' ? 'selected' : '' }}>Clientes sem pedidos</option>
                        <option value="custom" {{ old('target_audience') == 'custom' ? 'selected' : '' }}>Lista personalizada</option>
                    </select>
                    @error('target_audience')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Mensagem *
                </label>
                <textarea name="message" id="message" rows="6" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent @error('message') border-red-500 @enderror"
                          placeholder="Digite sua mensagem promocional...">{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="flex items-center">
                    <input type="hidden" name="schedule" value="0">
                    <input type="checkbox" name="schedule" value="1" {{ old('schedule') ? 'checked' : '' }} 
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="ml-2 text-sm text-gray-700">Agendar envio</span>
                </label>
            </div>

            <div id="schedule-fields" class="mt-4 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Data
                        </label>
                        <input type="date" name="scheduled_date" id="scheduled_date" 
                               value="{{ old('scheduled_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="scheduled_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Horário
                        </label>
                        <input type="time" name="scheduled_time" id="scheduled_time" 
                               value="{{ old('scheduled_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.whatsapp.notifications.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-all">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-all shadow-sm hover:shadow-md">
                    <i class="bi bi-send mr-2"></i> Enviar Promoção
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('target_audience').addEventListener('change', function() {
    const customFields = document.getElementById('custom-fields');
    if (this.value === 'custom') {
        customFields.classList.remove('hidden');
    } else {
        customFields.classList.add('hidden');
    }
});

document.querySelector('input[name="schedule"]').addEventListener('change', function() {
    const scheduleFields = document.getElementById('schedule-fields');
    if (this.checked) {
        scheduleFields.classList.remove('hidden');
    } else {
        scheduleFields.classList.add('hidden');
    }
});
</script>
@endsection

