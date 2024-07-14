<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex flex-wrap gap-6">
            <!-- Sessão de Collections -->
            <div class="flex-grow lg:w-2/3">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Collections</h2>

                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($collections as $collection)
                        <a href="{{ route('collections.show', $collection->id) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 block hover:shadow-lg transition-shadow duration-300">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $collection->name }}</h3>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $collection->videos->count() }} videos • {{ $collection->storage_used }} storage</p>
                                </div>
                                <div>
                                    <!-- Placeholder para ícone de opções -->
                                    <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h.01M12 12h.01M18 12h.01"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <img class="h-8 w-8 rounded-full" src="{{ $collection->members->first()->profile_photo_url ?? asset('path/to/default/avatar.png') }}" alt="User Avatar">
                                <div class="ml-3">
                                    <p class="text-sm text-gray-700 dark:text-gray-400">
                                        {{ $collection->members->first()->name ?? 'No member' }} uploaded {{ $collection->videos->count() }} videos
                                        @if ($collection->last_video_uploaded_at)
                                            <span class="text-gray-500 dark:text-gray-400">{{ $collection->last_video_uploaded_at->diffForHumans() }}</span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">No uploads yet</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            <!-- Sessão de Recent Activity e Usage -->
            <div class="flex flex-col gap-6 lg:w-1/3">
                <!-- Sessão de Recent Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <h2 class="text-2xl font-semibold mb-4">Recent Activity</h2>
                    <ul class="space-y-4">
                        <hr class="border-gray-300 dark:border-gray-600 m-8">
                        @foreach ($recentUploads as $upload)
                            <li>
                                <div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-2">
                                            <img class="h-10 w-10 rounded-full" src="{{ $upload->user->profile_photo_url }}" alt="{{ $upload->user->name }}">
                                            <div class="text-sm font-medium text-gray-900">{{ $upload->user->name }}</div>
                                        </div>
                                        <div class="text-sm text-gray-400">{{ $upload->created_at->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $upload->title }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- Sessão de Usage -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <div class="mb-4">
                        <!-- Cabeçalho -->
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-semibold">Usage</h2>
                            <button class="filament-button">Manage plan</button>
                        </div>
                        <hr class="border-gray-300 dark:border-gray-600">
                    </div>
                    <div class="mb-4">
                        <!-- Corpo -->
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900">Storage Amount</span>
                                    <span class="text-sm font-medium text-gray-500">{{ number_format($totalStorage, 2) }} GB / {{ $maxStorage }} TB</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 mt-2">
                                    <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: {{ ($totalStorage / ($maxStorage * 1024)) * 100 }}%"></div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900">Monthly Bandwidth</span>
                                    <span class="text-sm font-medium text-gray-500">{{ number_format($totalBandwidth, 2) }} TB / {{ $maxBandwidth }} TB</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 mt-2">
                                    <div class="bg-green-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: {{ ($totalBandwidth / $maxBandwidth) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <hr class="border-gray-300 dark:border-gray-600 m-8">
                    </div>
                    <div class="mt-8">
                        <!-- Rodapé -->
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Your next payment is on <span class="text-gray-900 dark:text-white">{{ $nextPaymentDate->format('F jS') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
