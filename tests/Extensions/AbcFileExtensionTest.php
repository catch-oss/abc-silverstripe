<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\AbcFileExtension;
use SilverStripe\Assets\File;
use SilverStripe\Dev\SapphireTest;

class AbcFileExtensionTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testExtensionIsApplied(): void
    {
        // GIVEN the AbcFileExtension is configured in YAML
        // WHEN we check if File has the extension
        $hasExtension = File::has_extension(AbcFileExtension::class);

        // THEN it should be applied
        $this->assertTrue($hasExtension);
    }

    public function testGetAllowedExtensionsReturnsArray(): void
    {
        // GIVEN the AbcFileExtension class
        // WHEN we get the allowed extensions
        $extensions = AbcFileExtension::get_allowed_extensions();

        // THEN it should return a non-empty array
        $this->assertIsArray($extensions);
        $this->assertNotEmpty($extensions);
        $this->assertContains('pdf', $extensions);
        $this->assertContains('jpg', $extensions);
        $this->assertContains('png', $extensions);
    }
}
