<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Brand;
use App\Models\Tag;

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
        View::composer('layouts.main', function ($view) {
            $brands = Brand::all(); // Recupera todas las marcas
            $tags = Tag::all(); // Recupera todos los tags
            
            $view->with([
                'brands' => $brands,
                'tags' => $tags,
            ]);    
        });
    }
}
