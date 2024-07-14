<div class="flex items-center space-x-3">
    <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
    <div>
        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
        <div class="text-sm text-gray-500">{{ $user->email }}</div>
    </div>
</div>
