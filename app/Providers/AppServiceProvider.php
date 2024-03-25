<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;

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
        Paginator::useBootstrapFive();

        Collection::macro('paginate', function ($perPage = 15, $options = [],  $page = null) {
        $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $paginator = new LengthAwarePaginator(
            items: $this->forPage($page, $perPage),
            total: $this->count(),
            perPage: $perPage,
            currentPage: $page,
            options: $options
        );

        return $paginator->withPath(Request::url());
    });
    }
}
