@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6 border border-gray-100">
            <div class="h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
            <div class="px-6 pb-6">
                <div class="relative flex justify-between items-end -mt-16 mb-4">
                    <div class="w-28 h-28 bg-white p-1 rounded-full shadow-xl ring-4 ring-white">
                        <div class="w-full h-full bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center overflow-hidden">
                            @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" class="w-full h-full object-cover">
                            @else
                            <i class="fas fa-user text-4xl text-indigo-600"></i>
                            @endif
                        </div>
                    </div>

                    <div class="flex space-x-2 mb-2">
                        <div class="px-5 py-2 bg-green-50 text-green-700 rounded-lg font-medium border border-green-200 flex items-center text-sm">
                            <i class="fas fa-check mr-1.5"></i>Connected
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 mb-0.5">{{ $user->first_name }} {{ $user->last_name }}</h1>
                    <p class="text-gray-600 text-base mb-2">{{ $user->industry ?? 'Professional' }}</p>
                    @if($user->location)
                    <div class="flex items-center text-gray-500">
                        <i class="fas fa-map-marker-alt text-xs mr-1.5"></i>
                        <span class="text-sm">{{ $user->location }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex space-x-8 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $user->posts_count }}</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Posts</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $user->likes_count }}</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Likes</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-gray-900">{{ $user->connections_count ?? 0 }}</p>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Connections</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar - Recent Activity -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-5 border border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-chart-line text-white text-sm"></i>
                        </div>
                        Recent Activity
                    </h3>
                    <div class="space-y-2.5">
                        @forelse($interactions as $action)
                        <div class="bg-gradient-to-r from-gray-50 to-gray-50 hover:from-indigo-50 hover:to-purple-50 p-3 rounded-xl transition-all border border-gray-100 hover:border-indigo-200">
                            <div class="flex items-center space-x-2.5">
                                <div class="flex-shrink-0">
                                    @if($action->action_type == 'like')
                                    <div class="w-8 h-8 bg-gradient-to-br from-red-400 to-pink-500 rounded-lg flex items-center justify-center shadow-sm">
                                        <i class="fas fa-heart text-white text-xs"></i>
                                    </div>
                                    @else
                                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center shadow-sm">
                                        <i class="fas fa-comment text-white text-xs"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs text-gray-700 leading-relaxed">
                                        <span class="font-semibold text-gray-900">{{ $action->action_type == 'like' ? 'Liked' : 'Commented on' }}</span> {{ $action->post->user->first_name }}'s post
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $action->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-clock text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-gray-500 text-xs font-medium">No recent activity</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Main Content - Posts -->
            <div class="lg:col-span-2">
                <div class="mb-5">
                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-newspaper text-white text-sm"></i>
                        </div>
                        Posts
                    </h3>
                </div>

                <div class="space-y-4">
                    @forelse($user->posts as $post)
                    @if($post->status === 'approved')
                    @include('partials.post-card', ['post' => $post])
                    @endif
                    @empty
                    <div class="mt-6">
                        {{ $user->posts->links() }}
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-10 text-center border border-gray-100">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-pencil-alt text-indigo-600 text-xl"></i>
                        </div>
                        <h4 class="text-base font-bold text-gray-900 mb-1">No posts yet</h4>
                        <p class="text-gray-500 text-sm">{{ $user->first_name }} hasn't shared anything yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection