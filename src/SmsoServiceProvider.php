<?php

namespace NotificationChannels\Smso;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class SmsoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(SmsoChannel::class)
            ->needs(Smso::class)
            ->give(function () {
                $apiKey = config('services.smso.api_key');

                return new Smso(
                    $apiKey,
                    new HttpClient()
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
