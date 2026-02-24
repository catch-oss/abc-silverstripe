<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Classes\LeftAndMainHelper;
use Azt3k\SS\Extensions\AbcLeftAndMainExtension;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\View\Requirements;

class AbcLeftAndMainExtensionTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testOnAfterInitCallsProcessRequirements(): void
    {
        // GIVEN a LeftAndMainHelper with a blocked requirement
        LeftAndMainHelper::require_block('test/leftandmain-ext.js');

        $ext = new AbcLeftAndMainExtension();

        // WHEN onAfterInit is called
        $ext->onAfterInit();

        // THEN the blocked requirement should have been processed via Requirements
        $blocked = Requirements::backend()->getBlocked();
        $this->assertContains('test/leftandmain-ext.js', $blocked);
    }
}
