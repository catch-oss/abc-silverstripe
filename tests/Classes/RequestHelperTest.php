<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\RequestHelper;
use PHPUnit\Framework\TestCase;

class RequestHelperTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_SERVER['HTTP_USER_AGENT']);
        parent::tearDown();
    }

    public function testIsIeReturnsTrueForIeUserAgent(): void
    {
        // GIVEN an IE user agent string
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)';

        // WHEN we check for IE
        $result = RequestHelper::is_ie();

        // THEN it should return true
        $this->assertTrue($result);
    }

    public function testIsIeReturnsFalseForModernBrowser(): void
    {
        // GIVEN a Chrome user agent string
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh) AppleWebKit/537.36 Chrome/120.0.0.0';

        // WHEN we check for IE
        $result = RequestHelper::is_ie();

        // THEN it should return false
        $this->assertFalse($result);
    }

    public function testIsIeReturnsFalseWhenNoUserAgent(): void
    {
        // GIVEN no user agent is set
        unset($_SERVER['HTTP_USER_AGENT']);

        // WHEN we check for IE
        $result = RequestHelper::is_ie();

        // THEN it should return false
        $this->assertFalse($result);
    }
}
