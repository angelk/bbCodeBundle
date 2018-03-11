<?php

use PHPUnit\Framework\TestCase;
use Potaka\BbcodeBundle\BbCode\TextToHtml;
use Psr\Cache\CacheItemPoolInterface;
use Potaka\BbcodeBundle\BbCode\TextToHtmlCache;
use Psr\Cache\CacheItemInterface;

class TextToHtmlCacheTest extends TestCase
{
    public function testGetHtmlWithCache()
    {
        $textToHtmlMockBuilder = $this->getMockBuilder(TextToHtml::class);
        $textToHtmlMockBuilder->disableOriginalConstructor();
        $textToHtmlMock = $textToHtmlMockBuilder->getMock();

        $cachePoolMockBuilder = $this->getMockBuilder(CacheItemPoolInterface::class);
        $cachePoolMockBuilder->setMethods(['getItem']);
        $cachePoolMock = $cachePoolMockBuilder->getMockForAbstractClass();

        $cacheItemMockBuilder = $this->getMockBuilder(CacheItemInterface::class);
        $cacheItemMockBuilder->setMethods(
            [
                'isHit',
                'get',
            ]
        );
        $cacheItemMock = $cacheItemMockBuilder->getMockForAbstractClass();
        $cacheItemMock->expects($this->once())->method('isHit')->willReturn(true);
        $cacheItemMock->expects($this->once())->method('get')->willReturn('<b>test</b>');

        $cachePoolMock->expects($this->once())
            ->method('getItem')
            ->with('80d4dcf9ecbd6298cb7ee8a9068e92ab')
            ->willReturn($cacheItemMock);

        $textToHtmlCache = new TextToHtmlCache($textToHtmlMock, $cachePoolMock);
        $html = $textToHtmlCache->getHtml('[b]test[/b]');

        $this->assertSame('<b>test</b>', $html);
    }

    public function testGetHtmlWithNoCache()
    {
        $textToHtmlMockBuilder = $this->getMockBuilder(TextToHtml::class);
        $textToHtmlMockBuilder->disableOriginalConstructor();
        $textToHtmlMockBuilder->setMethods(['getHtml']);
        $textToHtmlMock = $textToHtmlMockBuilder->getMock();
        $textToHtmlMock->expects($this->once())->method('getHtml')->willReturn('<b>test</b>');

        $cacheItemMockBuilder = $this->getMockBuilder(CacheItemInterface::class);
        $cacheItemMockBuilder->setMethods(
            [
                'isHit',
                'set',
            ]
        );

        $cacheItemMock = $cacheItemMockBuilder->getMockForAbstractClass();
        $cacheItemMock->expects($this->once())->method('isHit')->willReturn(false);
        $cacheItemMock->expects($this->once())->method('set')->with('<b>test</b>');
        $cacheItemMock->expects($this->once())->method('expiresAfter')->with(600);

        $cachePoolMockBuilder = $this->getMockBuilder(CacheItemPoolInterface::class);
        $cachePoolMockBuilder->setMethods(['getItem', 'save']);
        $cachePoolMock = $cachePoolMockBuilder->getMockForAbstractClass();
        $cachePoolMock->expects($this->once())->method('getItem')->willReturn($cacheItemMock);
        $cachePoolMock->expects($this->once())->method('save')->with($cacheItemMock);

        $textToHtmlCache = new TextToHtmlCache($textToHtmlMock, $cachePoolMock);
        $html = $textToHtmlCache->getHtml('[b]test[/b]');

        $this->assertSame('<b>test</b>', $html);
    }

    public function testGetHtmlWithCachePrefix()
    {
        $textToHtmlMockBuilder = $this->getMockBuilder(TextToHtml::class);
        $textToHtmlMockBuilder->disableOriginalConstructor();
        $textToHtmlMock = $textToHtmlMockBuilder->getMock();

        $cachePoolMockBuilder = $this->getMockBuilder(CacheItemPoolInterface::class);
        $cachePoolMockBuilder->setMethods(['getItem']);
        $cachePoolMock = $cachePoolMockBuilder->getMockForAbstractClass();

        $cacheItemMockBuilder = $this->getMockBuilder(CacheItemInterface::class);
        $cacheItemMockBuilder->setMethods(
            [
                'isHit',
                'get',
            ]
        );
        $cacheItemMock = $cacheItemMockBuilder->getMockForAbstractClass();
        $cacheItemMock->expects($this->once())->method('isHit')->willReturn(true);
        $cacheItemMock->expects($this->once())->method('get')->willReturn('<b>test</b>');

        $cachePoolMock->expects($this->once())
            ->method('getItem')
            ->with('my-prefix.80d4dcf9ecbd6298cb7ee8a9068e92ab')
            ->willReturn($cacheItemMock);

        $textToHtmlCache = new TextToHtmlCache($textToHtmlMock, $cachePoolMock);
        $textToHtmlCache->setCachePrefix('my-prefix.');

        $html = $textToHtmlCache->getHtml('[b]test[/b]');

        $this->assertSame('<b>test</b>', $html);
    }

    public function testGetHtmlWithCachePrefixGetter()
    {
        $textToHtmlMockBuilder = $this->getMockBuilder(TextToHtml::class);
        $textToHtmlMockBuilder->disableOriginalConstructor();
        $textToHtmlMock = $textToHtmlMockBuilder->getMock();

        $cachePoolMockBuilder = $this->getMockBuilder(CacheItemPoolInterface::class);
        $cachePoolMock = $cachePoolMockBuilder->getMockForAbstractClass();

        $textToHtmlCache = new TextToHtmlCache($textToHtmlMock, $cachePoolMock);
        $textToHtmlCache->setCachePrefix('my-prefix.');

        $this->assertSame(
            'my-prefix.',
            $textToHtmlCache->getCachePrefix()
        );
    }
}
