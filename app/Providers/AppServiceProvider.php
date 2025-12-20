<?php

namespace App\Providers;

use App\Models\Post;
use App\Observers\ObserverPost;
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
        Post::observe(ObserverPost::class);
    }


}
