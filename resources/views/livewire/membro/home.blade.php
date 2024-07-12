<div class="container mx-auto py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-bold">Collections</h1>
                <button class="btn btn-primary">+ Create Collection</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($collections as $collection)
                    <div class="bg-gray-800 text-white p-4 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold">{{ $collection->name }}</h2>
                        <p>{{ $collection->videos_count }} videos • {{ $collection->storage_size }} storage</p>
                        <a href="{{ route('collection.videos', $collection->id) }}" class="btn btn-secondary mt-2">View Videos</a>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-span-1">
            <div class="bg-gray-800 text-white p-4 rounded-lg shadow-md mb-4">
                <h2 class="text-xl font-semibold mb-2">Recent Activity</h2>
                <ul>
                    @foreach ($recentActivities as $activity)
                        <li class="mb-2">
                            <span class="block">{{ $activity->description }}</span>
                            <span class="text-gray-400 text-sm">{{ $activity->created_at->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-gray-800 text-white p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2">Usage</h2>
                <div class="mb-4">
                    <span class="block font-semibold">Storage amount</span>
                    <div class="w-full bg-gray-600 h-2 rounded-full">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($usage->total_size / 6000) * 100 }}%"></div>
                    </div>
                    <span class="text-gray-400 text-sm">{{ $usage->total_size / 1000 }}TB / 6TB</span>
                </div>
                <div>
                    <span class="block font-semibold">Monthly bandwidth</span>
                    <div class="w-full bg-gray-600 h-2 rounded-full">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 46%"></div>
                    </div>
                    <span class="text-gray-400 text-sm">23TB / 50TB</span>
                </div>
            </div>
        </div>
    </div>
</div>
