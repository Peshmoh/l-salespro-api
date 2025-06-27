<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\LeyscoHelpers;

class LeyscoHelperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Make helpers callable via app(LeyscoHelpers::class)
        $this->app->singleton(LeyscoHelpers::class, fn() => new LeyscoHelpers);
    }

    public function boot(): void
    {
        //
    }
}
