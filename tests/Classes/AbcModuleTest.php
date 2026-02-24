<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\AbcModule;
use PHPUnit\Framework\TestCase;

class AbcModuleTest extends TestCase
{
    public function testLoadThrowsRuntimeException(): void
    {
        // GIVEN the deprecated AbcModule class
        // WHEN we try to load any module
        // THEN it should throw a RuntimeException
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('no longer supported');
        AbcModule::load('jquery');
    }

    public function testCombineIsNoOp(): void
    {
        // GIVEN the deprecated AbcModule class
        // WHEN we call combine()
        AbcModule::combine();

        // THEN it should do nothing (no-op stub for backwards compatibility)
        $this->assertTrue(true);
    }
}
