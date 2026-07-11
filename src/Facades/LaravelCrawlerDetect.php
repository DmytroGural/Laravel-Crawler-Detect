<?php

namespace Jaybizzle\LaravelCrawlerDetect\Facades;

use Illuminate\Support\Facades\Facade;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

/**
 * @method static bool isCrawler(string|null $userAgent = null)
 * @method static string|null getMatches()
 * @method static string|null getUserAgent()
 * @method static string|null setUserAgent(string|null $userAgent = null)
 * @method static void setHttpHeaders(array|null $httpHeaders = null)
 * @method static array getUaHttpHeaders()
 *
 * @see CrawlerDetect
 */
class LaravelCrawlerDetect extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CrawlerDetect::class;
    }
}
