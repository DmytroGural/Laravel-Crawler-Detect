<?php

namespace Jaybizzle\LaravelCrawlerDetect\Tests;

use Illuminate\Http\Request;
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

    public function test_request_macro_detects_crawler_from_request_headers()
    {
        foreach ($this->fixture('crawlers.txt') as $userAgent) {
            $request = Request::create('/', 'GET', [], [], [], [
                'HTTP_USER_AGENT' => $userAgent,
            ]);
            $this->assertTrue($request->isCrawler());
        }
    }

    public function test_request_macro_can_override_user_agent()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        ]);

        $this->assertFalse($request->isCrawler());

        foreach ($this->fixture('devices.txt') as $userAgent) {
            $this->assertFalse($request->isCrawler($userAgent));
        }

        foreach ($this->fixture('crawlers.txt') as $userAgent) {
            $this->assertTrue($request->isCrawler($userAgent));
        }
    }

    public function test_request_macro_preserves_matches_state()
    {
        $request = Request::create('/', 'GET');
        $this->assertTrue($request->isCrawler('Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)'));
        $this->assertNotNull($request->crawler()->getMatches());
        $this->assertSame('Googlebot', $request->crawler()->getMatches());
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
