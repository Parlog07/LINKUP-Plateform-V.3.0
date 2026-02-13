<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendInviteController;

Route::get('/', function () {
    return view('auth.welcome');
})->name("welcome");

// Use DashboardController for dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    //send the friend request
    Route::post('/connect/{id}', [DashboardController::class, 'sendFriendRequest'])->name('connections.store');


    // connections page
    Route::get('/connections', [DashboardController::class, 'connections'])->name('connections');

    //actions
    Route::post('/connections/{id}/accept', [DashboardController::class, 'acceptRequest'])->name('connection.accept');
    Route::post('/connections/{id}/reject', [DashboardController::class, 'rejectRequest'])->name('connection.reject');
    Route::delete('/connections/{id}/cancel', [DashboardController::class, 'cancelRequest'])->name('connection.cancel');
    Route::delete('/connections/{id}/remove', [DashboardController::class, 'removeConnection'])->name('connection.remove');


    //profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])
    ->name('profile.show');


    
    Route::get('/posts', [App\Http\Controllers\PostController::class, 'index'])->name('posts.index');
    Route::get('/feeds', [App\Http\Controllers\PostController::class, 'feeds'])->middleware('auth')->name('posts.feeds');
Route::post('/posts', [App\Http\Controllers\PostController::class, 'store'])->name('posts.store');
Route::delete('/posts/{post}', [App\Http\Controllers\PostController::class, 'destroy'])->name('posts.destroy');
Route::put('/posts/{post}', [App\Http\Controllers\PostController::class, 'edit'])->name('posts.edit');


// Likes
    Route::post('/posts/{id}/like', [App\Http\Controllers\PostActionController::class, 'toggleLike'])->name('posts.like');

    // Comments
    Route::post('/posts/{id}/comment', [App\Http\Controllers\PostActionController::class, 'storeComment'])->name('posts.comment');
    Route::delete('/comments/{id}', [App\Http\Controllers\PostActionController::class, 'destroyComment'])->name('comments.destroy');

    Route::get('/notifications', [App\Http\Controllers\PostController::class, 'notifications'])
    ->name('notifications')
    ->middleware('auth');


});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [App\Http\Controllers\Admin\PostAdminController::class, 'index'])->name('admin.index');
    Route::post('admin/{post}/update-status', [App\Http\Controllers\Admin\PostAdminController::class, 'updateStatus'])->name('admin.updateStatus');
});

Route::middleware('auth')->group(function () {
        Route::get('/messages/attachments/{message}/preview', [App\Http\Controllers\MessageController::class, 'previewAttachment'])
        ->name('messages.attachments.preview');

        Route::get('/messages/attachments/{message}', [App\Http\Controllers\MessageController::class, 'downloadAttachment'])
        ->name('messages.attachments.download');

        Route::get('/messages/{user}', [App\Http\Controllers\MessageController::class, 'show'])
        ->whereNumber('user')
        ->name('messages.show');
});




Route::middleware('auth')->group(function () {
    Route::post('/friends/invite', [FriendInviteController::class, 'generate'])
        ->name('friends.invite.generate');

    Route::get('/friends/invite/accept', [FriendInviteController::class, 'accept'])
        ->middleware('signed') // important
        ->name('friends.invite.accept');
});
// Route::get('/friends/invite-test', function () {
//     return view('invite-test');
// })->middleware('auth');



require __DIR__.'/auth.php';
