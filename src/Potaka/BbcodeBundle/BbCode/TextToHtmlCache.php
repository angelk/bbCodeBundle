<?php

namespace Potaka\BbcodeBundle\BbCode;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Similar to TextToHtml, but with caching functionality
 *
 * @author po_taka
 */
class TextToHtmlCache implements TextToHtmlInterface
{
    /**
     * @var TextToHtml
     */
    private $textToHtml;

    /**
     * @var CacheItemPoolInterface
     */
    private $cachePool;

    /**
     * @var string
     */
    private $cachePrefix = '';

    /**
     * @var int ttl of the cache
     */
    private $cacheExpireTime;

    /**
     * @param \Potaka\BbcodeBundle\BbCode\TextToHtml $textToHtml
     * @param CacheItemPoolInterface $cachePool
     * @param int $expireAfter
     */
    public function __construct(TextToHtml $textToHtml, CacheItemPoolInterface $cachePool, int $expireAfter = 600) {

        $this->cachePool = $cachePool;
        $this->textToHtml = $textToHtml;
        $this->cacheExpireTime = $expireAfter;
    }

    /**
     *
     * @return string cache prefix for cache key
     */
    public function getCachePrefix() : string
    {
        return $this->cachePrefix;
    }

    /**
     * set cache prefix for cache keys
     *
     * @param string $cachePrefix
     */
    public function setCachePrefix(string $cachePrefix)
    {
        $this->cachePrefix = $cachePrefix;
    }

    public function getHtml(string $text): string
    {
        $cachedKey = $this->cachePrefix . md5($text);
        $cachedItem = $this->cachePool->getItem($cachedKey);
        if ($cachedItem->isHit()) {
            return $cachedItem->get();
        }

        $bbCodeTransformed = $this->textToHtml->getHtml($text);
        $cachedItem->set($bbCodeTransformed);
        $cachedItem->expiresAfter($this->cacheExpireTime);

        $this->cachePool->save($cachedItem);
    }
}
