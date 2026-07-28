<?php

namespace Jaybizzle\LaravelCrawlerDetect;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
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
        $this->app->singleton(CrawlerDetect::class, function (Application $app) {
            return new CrawlerDetect($app['request']->server());
        });

        $this->app->alias(CrawlerDetect::class, 'LaravelCrawlerDetect');
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Request::macro('crawler', function (?string $userAgent = null) {
            $crawler = app(CrawlerDetect::class);

            $crawler->setHttpHeaders($this->server->all());
            $crawler->setUserAgent($userAgent ?? $this->userAgent());

            return $crawler;
        });

        Request::macro('isCrawler', function (?string $userAgent = null) {
            return $this->crawler($userAgent)->isCrawler();
        });
    }
}
