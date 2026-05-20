<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\Repositories\CabangRepositoryInterface::class,
            \App\Repositories\CabangRepository::class,
        );
        $this->app->bind(
            \App\Contracts\Repositories\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class,
        );
        $this->app->bind(
            \App\Contracts\Repositories\PeriodeLaporanRepositoryInterface::class,
            \App\Repositories\PeriodeLaporanRepository::class,
        );
        $this->app->bind(
            \App\Contracts\Repositories\LaporanRepositoryInterface::class,
            \App\Repositories\LaporanRepository::class,
        );
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination::tailwind');
        Paginator::defaultSimpleView('pagination::simple-tailwind');

        // ── Model strict mode di development ───────────────────
        \Illuminate\Database\Eloquent\Model::shouldBeStrict(
            ! app()->isProduction()
        );
    }
}
