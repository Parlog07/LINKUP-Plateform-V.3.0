@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Notifications</h1>
        <p class="text-gray-600">Track the moderation status of your posts</p>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Post Status</h2>
        </div>

        <div class="p-6 space-y-4">
            @forelse($posts as $post)
                <div class="flex items-start justify-between border border-gray-200 rounded-xl p-4 hover:shadow transition">

                    <!-- Post Content -->
                    <div class="max-w-xl">
                        <p class="text-gray-800 text-sm line-clamp-2">
                            {{ $post->content ?? 'Media post' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Posted {{ $post->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @if($post->status === 'approved')
                            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm">
                                <i class="fas fa-check-circle mr-1"></i> Approved
                            </span>
                        @elseif($post->status === 'pending')
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg text-sm">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        @else
                            <span class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm">
                                <i class="fas fa-ban mr-1"></i> Blocked
                            </span>
                        @endif
                    </div>

                </div>
            @empty
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell text-indigo-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No notifications</h3>
                    <p class="text-gray-600">You have no post moderation updates yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
