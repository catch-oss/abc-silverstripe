<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\AbcSiteTreeExtension;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\SapphireTest;

class AbcSiteTreeExtensionTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testExtensionIsApplied(): void
    {
        // GIVEN the AbcSiteTreeExtension is configured in YAML
        // WHEN we check if SiteTree has the extension
        $hasExtension = SiteTree::has_extension(AbcSiteTreeExtension::class);

        // THEN it should be applied
        $this->assertTrue($hasExtension);
    }
}
