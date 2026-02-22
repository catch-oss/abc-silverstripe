<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\AbcURL;
use PHPUnit\Framework\TestCase;

class AbcURLTest extends TestCase
{
    public function testConstructorSetsUrl(): void
    {
        // GIVEN a URL string
        $url = 'https://example.com/page';

        // WHEN we create an AbcURL instance
        $abcUrl = new AbcURL($url);

        // THEN the URL and originalURL should be set
        $this->assertSame($url, $abcUrl->URL);
        $this->assertSame($url, $abcUrl->originalURL);
    }

    public function testStaticGetReturnsInstance(): void
    {
        // GIVEN a URL string
        $url = '/test/page';

        // WHEN we use the static get factory
        $abcUrl = AbcURL::get($url);

        // THEN it should return an AbcURL instance
        $this->assertInstanceOf(AbcURL::class, $abcUrl);
        $this->assertSame($url, $abcUrl->URL);
    }

    public function testQueryParamsAreAppended(): void
    {
        // GIVEN a URL without query params
        $abcUrl = new AbcURL('/page');

        // WHEN we add query parameters
        $result = $abcUrl->q(['foo' => 'bar', 'baz' => '1']);

        // THEN the URL should have query params appended
        $this->assertStringContains('foo=bar', $result->URL);
        $this->assertStringContains('baz=1', $result->URL);
    }

    public function testQueryParamsMergeWithExisting(): void
    {
        // GIVEN a URL with existing query params
        $abcUrl = new AbcURL('/page?existing=yes');

        // WHEN we add new query parameters
        $result = $abcUrl->q(['new' => 'param']);

        // THEN the URL should have both old and new params
        $this->assertStringContains('existing=yes', $result->URL);
        $this->assertStringContains('new=param', $result->URL);
    }

    public function testQueryParamsOverrideExisting(): void
    {
        // GIVEN a URL with existing query params
        $abcUrl = new AbcURL('/page?key=old');

        // WHEN we add a query param with the same key
        $result = $abcUrl->q(['key' => 'new']);

        // THEN the new value should override the old
        $this->assertStringContains('key=new', $result->URL);
        $this->assertStringNotContains('key=old', $result->URL);
    }

    public function testChainingReturnsInstance(): void
    {
        // GIVEN a URL
        $abcUrl = new AbcURL('/page');

        // WHEN we chain q() calls
        $result = $abcUrl->q(['a' => '1'])->q(['b' => '2']);

        // THEN the result should be an AbcURL with both params
        $this->assertInstanceOf(AbcURL::class, $result);
        $this->assertStringContains('a=1', $result->URL);
        $this->assertStringContains('b=2', $result->URL);
    }

    public function testBuildUrlWithFullComponents(): void
    {
        // GIVEN URL components
        $data = [
            'scheme' => 'https',
            'host' => 'example.com',
            'path' => '/page',
            'query' => 'key=value',
            'fragment' => 'section',
        ];

        // WHEN we build a URL from components
        $result = AbcURL::buildURL($data);

        // THEN it should return a properly formatted URL
        $this->assertSame('https://example.com/page?key=value#section', $result);
    }

    public function testBuildUrlWithMinimalComponents(): void
    {
        // GIVEN only a path component
        $data = ['path' => '/page'];

        // WHEN we build a URL
        $result = AbcURL::buildURL($data);

        // THEN it should return just the path
        $this->assertSame('/page', $result);
    }

    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(
            str_contains($haystack, $needle),
            "Failed asserting that '{$haystack}' contains '{$needle}'"
        );
    }

    private function assertStringNotContains(string $needle, string $haystack): void
    {
        $this->assertFalse(
            str_contains($haystack, $needle),
            "Failed asserting that '{$haystack}' does not contain '{$needle}'"
        );
    }
}
