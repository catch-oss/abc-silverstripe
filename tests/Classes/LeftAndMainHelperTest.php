<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\LeftAndMainHelper;
use Azt3k\SS\Classes\RequirementsHelper;
use SilverStripe\Dev\SapphireTest;

class LeftAndMainHelperTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testExtendsRequirementsHelper(): void
    {
        // GIVEN the LeftAndMainHelper class
        // WHEN we check its inheritance
        // THEN it should extend RequirementsHelper
        $this->assertTrue(is_subclass_of(LeftAndMainHelper::class, RequirementsHelper::class));
    }

    public function testHasOwnExtraRequirements(): void
    {
        // GIVEN the LeftAndMainHelper class
        // WHEN we get its requirements
        $reqs = LeftAndMainHelper::get_requirements();

        // THEN it should have block and unblock arrays
        $this->assertArrayHasKey('block', $reqs);
        $this->assertArrayHasKey('unblock', $reqs);
        $this->assertIsArray($reqs['block']);
        $this->assertIsArray($reqs['unblock']);
    }

    public function testRequireBlockAddsToBlockList(): void
    {
        // GIVEN the LeftAndMainHelper class
        // WHEN we block a requirement
        LeftAndMainHelper::require_block('test/helper-block.css');

        // THEN it should appear in the block list
        $reqs = LeftAndMainHelper::get_requirements();
        $this->assertContains('test/helper-block.css', $reqs['block']);
    }

    public function testRequireUnblockAddsToUnblockList(): void
    {
        // GIVEN the LeftAndMainHelper class
        // WHEN we unblock a requirement
        LeftAndMainHelper::require_unblock('test/helper-unblock.css');

        // THEN it should appear in the unblock list
        $reqs = LeftAndMainHelper::get_requirements();
        $this->assertContains('test/helper-unblock.css', $reqs['unblock']);
    }
}
