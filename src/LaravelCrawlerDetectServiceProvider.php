<?php

namespace Jaybizzle\LaravelCrawlerDetect;

use Illuminate\Support\ServiceProvider;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class LaravelCrawlerDetectServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CrawlerDetect::class, function ($app) {
            return new CrawlerDetect($app['request']->server());
        });

        $this->app->alias(CrawlerDetect::class, 'LaravelCrawlerDetect');
    }
}
