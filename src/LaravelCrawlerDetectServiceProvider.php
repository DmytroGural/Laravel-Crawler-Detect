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
        $this->app->singleton(CrawlerDetect::class, function (Application $app, array $parameters = []) {
            $headers = $parameters['headers'] ?? $app['request']->server();
            $userAgent = $parameters['userAgent'] ?? null;

            return new CrawlerDetect($headers, $userAgent);
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
        Request::macro('crawler', function (?array $headers = null, ?string $userAgent = null) {
            return app(CrawlerDetect::class, [
                'headers' => $headers,
                'userAgent' => $userAgent,
            ]);
        });

        Request::macro('isCrawler', function (?string $userAgent = null) {
            return $this->crawler(userAgent: $userAgent)->isCrawler();
        });
    }
}
