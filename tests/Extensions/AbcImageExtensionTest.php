<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\AbcImageExtension;
use SilverStripe\Assets\Image;
use SilverStripe\Dev\SapphireTest;

class AbcImageExtensionTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testExtensionIsApplied(): void
    {
        // GIVEN the AbcImageExtension is configured in YAML
        // WHEN we check if Image has the extension
        $hasExtension = Image::has_extension(AbcImageExtension::class);

        // THEN it should be applied
        $this->assertTrue($hasExtension);
    }

    public function testFallbackImageIsNull(): void
    {
        // GIVEN the AbcImageExtension class
        // WHEN we check the default fallback image
        $fallback = AbcImageExtension::$fallback_image;

        // THEN it should be set (configured in _config.php)
        $this->assertNotNull($fallback);
    }
}
