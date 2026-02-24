<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\AbcStr;
use PHPUnit\Framework\TestCase;

class AbcStrTest extends TestCase
{
    public function testConstructorSetsString(): void
    {
        // GIVEN a string value
        $input = 'Hello World';

        // WHEN we create an AbcStr instance
        $str = new AbcStr($input);

        // THEN the string and originalStr should be set
        $this->assertSame($input, $str->str);
        $this->assertSame($input, $str->originalStr);
    }

    public function testStaticGetReturnsInstance(): void
    {
        // GIVEN a string value
        $input = 'Test';

        // WHEN we use the static get factory
        $str = AbcStr::get($input);

        // THEN it should return an AbcStr instance with the value set
        $this->assertInstanceOf(AbcStr::class, $str);
        $this->assertSame($input, $str->str);
    }

    public function testLimitWordsWithDefault(): void
    {
        // GIVEN a string with many words and a default word limit of 50
        $words = implode(' ', array_fill(0, 60, 'word'));
        $str = AbcStr::get($words);

        // WHEN we call limitWords without a custom limit
        $result = $str->limitWords();

        // THEN it should truncate to 50 words with overflow indicator
        $this->assertStringEndsWith('...', (string) $result);
        // The result should be 50 "word" tokens + "..." appended to the last one
        $resultWords = explode(' ', (string) $result);
        $this->assertSame(50, count($resultWords));
    }

    public function testLimitWordsWithCustomLimit(): void
    {
        // GIVEN a string with 10 words
        $str = AbcStr::get('one two three four five six seven eight nine ten');

        // WHEN we limit to 3 words
        $result = $str->limitWords(3);

        // THEN it should return first 3 words with overflow indicator
        $this->assertSame('one two three...', (string) $result);
    }

    public function testLimitWordsNoOverflowWhenUnderLimit(): void
    {
        // GIVEN a short string under the limit
        $str = AbcStr::get('short text');

        // WHEN we limit to 5 words
        $result = $str->limitWords(5);

        // THEN it should return the full string without overflow indicator
        $this->assertSame('short text', (string) $result);
    }

    public function testLimitCharsWithDefault(): void
    {
        // GIVEN a string longer than 300 chars
        $input = str_repeat('a', 350);
        $str = AbcStr::get($input);

        // WHEN we call limitChars without a custom limit
        $result = $str->limitChars();

        // THEN it should truncate to 300 chars including overflow indicator
        $this->assertSame(300, strlen((string) $result));
        $this->assertStringEndsWith('...', (string) $result);
    }

    public function testLimitCharsWithCustomLimit(): void
    {
        // GIVEN a 20-char string
        $str = AbcStr::get('abcdefghijklmnopqrst');

        // WHEN we limit to 10 chars
        $result = $str->limitChars(10);

        // THEN it should truncate to 10 chars including overflow indicator
        $this->assertSame(10, strlen((string) $result));
        $this->assertSame('abcdefg...', (string) $result);
    }

    public function testLimitCharsNoOverflowWhenUnderLimit(): void
    {
        // GIVEN a short string under the limit
        $str = AbcStr::get('short');

        // WHEN we limit to 100 chars
        $result = $str->limitChars(100);

        // THEN it should return the original string without truncation
        $this->assertSame('short', (string) $result);
    }

    public function testLimitCharsNoDotDot(): void
    {
        // GIVEN a 20-char string
        $str = AbcStr::get('abcdefghijklmnopqrst');

        // WHEN we limit to 10 chars with no overflow indicator
        $result = $str->limitCharsNoDotDot(10);

        // THEN it should truncate to 10 chars with no dots
        $this->assertSame(10, strlen((string) $result));
        $this->assertSame('abcdefghij', (string) $result);
    }

    public function testToStringReturnsStr(): void
    {
        // GIVEN an AbcStr instance
        $str = AbcStr::get('test value');

        // WHEN we cast to string
        $result = (string) $str;

        // THEN it should return the str property
        $this->assertSame('test value', $result);
    }

    public function testChaining(): void
    {
        // GIVEN a long string
        $input = 'one two three four five six seven eight nine ten';

        // WHEN we chain limitWords and limitChars
        $result = (string) AbcStr::get($input)->limitWords(5)->limitChars(15);

        // THEN it should apply both limits
        $this->assertTrue(strlen($result) <= 15);
    }
}
