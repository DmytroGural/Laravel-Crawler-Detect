<?php

namespace Jaybizzle\LaravelCrawlerDetect\Tests;

use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Jaybizzle\LaravelCrawlerDetect\Facades\LaravelCrawlerDetect as Crawler;
use Jaybizzle\LaravelCrawlerDetect\LaravelCrawlerDetectServiceProvider;
use Orchestra\Testbench\TestCase;

class UATest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [LaravelCrawlerDetectServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Crawler' => Crawler::class,
        ];
    }

    public function test_it_registers_a_singleton()
    {
        $this->assertInstanceOf(CrawlerDetect::class, $this->app->make(CrawlerDetect::class));
        $this->assertSame($this->app->make(CrawlerDetect::class), $this->app->make('LaravelCrawlerDetect'));
    }

    public function test_bots_are_detected_as_crawlers()
    {
        foreach ($this->fixture('crawlers.txt') as $userAgent) {
            $this->assertTrue(Crawler::isCrawler($userAgent), $userAgent);
        }
    }

    public function test_devices_are_not_detected_as_crawlers()
    {
        foreach ($this->fixture('devices.txt') as $userAgent) {
            $this->assertFalse(Crawler::isCrawler($userAgent), $userAgent);
        }
    }

    /**
     * Load a list of user agents from the Crawler-Detect test suite.
     *
     * @param  string  $file
     * @return array
     */
    protected function fixture($file)
    {
        return file(
            'https://raw.githubusercontent.com/JayBizzle/Crawler-Detect/master/tests/data/user_agent/'.$file,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );
    }
}
