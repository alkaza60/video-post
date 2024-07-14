<x-filament-panels::page>
    <div class="space-y-6">
        <h2 class="text-2xl font-semibold">{{ $collection->name }}</h2>
        <p>{{ $collection->description }}</p>
        <p class="text-gray-500 dark:text-gray-400">{{ $collection->videos->count() }} videos • {{ $collection->storage_used }} storage</p>
        
        <!-- Tabela de Vídeos -->
        {{ $this->table }}
    </div>
</x-filament-panels::page>
