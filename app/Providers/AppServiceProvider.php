<?php

namespace App\Providers;

use App\Models\course_type;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        Paginator:: useBootstrapfive();

        View::composer('layout.front_layout', function ($view) {
            $view->with('courseTypes', self::courseTypes());
        });
    }

    public static function courseTypes()
    {
        return cache()->remember('nav_course_types_v2', 300, function () {
            return course_type::with(['courses' => function ($query) {
                $query->select(
                    'id',
                    'name',
                    'image',
                    'description',
                    'normal_price',
                    'special_price',
                    'duration',
                    'type'
                );
            }])
                ->orderBy('id')
                ->get(['id', 'name']);
        });
    }
}
