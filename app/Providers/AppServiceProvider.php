<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config()->set('livewire.temporary_file_upload.rules', 'file|max:51200|mimes:jpg,jpeg,png,gif,webp,mp4,mov,webm,m4v,avi,mkv,pdf,doc,docx,txt,zip,rar');
    }
}
