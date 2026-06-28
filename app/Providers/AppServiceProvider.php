<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\AssetRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        Gate::define('manage-assets', fn ($user) => $user->isAdmin());

        Gate::define('review-requests', fn ($user) => $user->isAdmin() || $user->isManager());

        Gate::define('create-requests', fn ($user) => $user->isEmployee());

        Gate::define('view-department-request', function ($user, AssetRequest $assetRequest) {
            if ($user->isAdmin()) {
                return true;
            }

            if ($user->isManager()) {
                return $assetRequest->user->department === $user->department;
            }

            return $assetRequest->user_id === $user->id;
        });

        Gate::define('view-assigned-assets', function ($user, Asset $asset) {
            if ($user->isAdmin()) {
                return true;
            }

            return $user->isEmployee() && $asset->user_id === $user->id;
        });
    }
}
