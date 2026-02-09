@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Admin Dashboard - Posts</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">User Posts</h2>
        </div>

        <div class="p-6">
            @if($posts->count() > 0)
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center overflow-hidden">
                                        @if($post->user->profile_picture)
                                            <img src="{{ asset('storage/' . $post->user->profile_picture) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-indigo-600 text-xl"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $post->user->first_name }} {{ $post->user->last_name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $post->user->username }}</p>
                                    </div>
                                </div>

                                <div>
                                    <span class="px-3 py-1 rounded-full text-xs
                                        @if($post->status == 'approved') bg-green-100 text-green-700
                                        @elseif($post->status == 'pending') bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4 text-gray-700">
                                <p>{{ $post->content }}</p>
                                @if($post->media_path)
                                    @if($post->media_type == 'image')
                                        <img src="{{ asset('storage/' . $post->media_path) }}" class="mt-2 rounded-lg max-h-60 w-full object-cover">
                                    @else
                                        <video controls class="mt-2 rounded-lg max-h-60 w-full">
                                            <source src="{{ asset('storage/' . $post->media_path) }}" type="video/mp4">
                                        </video>
                                    @endif
                                @endif
                            </div>

                            <form action="{{ route('admin.updateStatus', $post) }}" method="POST" class="flex items-center space-x-4">
                                @csrf
                                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="approved" {{ $post->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="pending" {{ $post->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="blocked" {{ $post->status == 'blocked' ? 'selected' : '' }}>Blocked</option>
                                </select>

                                <input type="text" name="moderation_reason" value="{{ $post->moderation_reason }}" placeholder="Reason (optional)" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">

                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    Update
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-file-alt text-indigo-600 text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No posts found</h3>
                    <p class="text-gray-600 mb-6">There are no posts to moderate.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
