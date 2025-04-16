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
            $brands = Brand::orderBy('nombre', 'asc')->get(); // Ordena marcas alfabéticamente
            $tags = Tag::orderBy('nombre', 'asc')->get();     // Ordena tags alfabéticamente
            
            $view->with([
                'brands' => $brands,
                'tags' => $tags,
            ]);    
        });

        View::composer('items.form', function ($view) {
            $brands = Brand::orderBy('nombre', 'asc')->get(); // Ordena marcas alfabéticamente
            $tags = Tag::orderBy('nombre', 'asc')->get();     // Ordena tags alfabéticamente
            
            $view->with([
                'brands' => $brands,
                'tags' => $tags,
            ]);    
        });

        View::composer('items.edit', function ($view) {
            $brands = Brand::orderBy('nombre', 'asc')->get(); // Ordena marcas alfabéticamente
            $tags = Tag::orderBy('nombre', 'asc')->get();     // Ordena tags alfabéticamente
            
            $view->with([
                'brands' => $brands,
                'tags' => $tags,
            ]);    
        });

        View::composer('items.list', function ($view) {
            $brands = Brand::orderBy('nombre', 'asc')->get(); // Ordena marcas alfabéticamente
            $tags = Tag::orderBy('nombre', 'asc')->get();     // Ordena tags alfabéticamente
            
            $view->with([
                'brands' => $brands,
                'tags' => $tags,
            ]);    
        });
    }
}
