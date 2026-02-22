<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\AbcControllerExtension;
use SilverStripe\Control\Controller;
use SilverStripe\Dev\SapphireTest;

class AbcControllerExtensionTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testExtensionIsApplied(): void
    {
        // GIVEN the AbcControllerExtension is configured in YAML
        // WHEN we check if Controller has the extension
        $hasExtension = Controller::has_extension(AbcControllerExtension::class);

        // THEN it should be applied
        $this->assertTrue($hasExtension);
    }
}
