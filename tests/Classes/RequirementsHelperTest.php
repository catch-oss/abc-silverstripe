<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\RequirementsHelper;
use SilverStripe\Dev\SapphireTest;

class RequirementsHelperTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testRequireBlockAddsFileToBlockList(): void
    {
        // GIVEN a clean requirements state
        // WHEN we block a file
        RequirementsHelper::require_block('test/file.js');

        // THEN the file should appear in the block requirements
        $reqs = RequirementsHelper::get_requirements();
        $this->assertContains('test/file.js', $reqs['block']);
    }

    public function testRequireBlockAcceptsArray(): void
    {
        // GIVEN multiple files to block
        $files = ['file1.js', 'file2.js'];

        // WHEN we block them as an array
        RequirementsHelper::require_block($files);

        // THEN both files should appear in the block list
        $reqs = RequirementsHelper::get_requirements();
        $this->assertContains('file1.js', $reqs['block']);
        $this->assertContains('file2.js', $reqs['block']);
    }

    public function testRequireUnblockAddsFileToUnblockList(): void
    {
        // GIVEN a clean requirements state
        // WHEN we unblock a file
        RequirementsHelper::require_unblock('test/unblock.js');

        // THEN the file should appear in the unblock requirements
        $reqs = RequirementsHelper::get_requirements();
        $this->assertContains('test/unblock.js', $reqs['unblock']);
    }

    public function testGetRequirementsReturnsStructuredArray(): void
    {
        // GIVEN the RequirementsHelper class
        // WHEN we get the requirements
        $reqs = RequirementsHelper::get_requirements();

        // THEN it should return an array with block and unblock keys
        $this->assertIsArray($reqs);
        $this->assertArrayHasKey('block', $reqs);
        $this->assertArrayHasKey('unblock', $reqs);
    }

    public function testRequireUnblockAcceptsArray(): void
    {
        // GIVEN multiple files to unblock
        $files = ['unblock1.js', 'unblock2.js'];

        // WHEN we unblock them as an array
        RequirementsHelper::require_unblock($files);

        // THEN both files should appear in the unblock list
        $reqs = RequirementsHelper::get_requirements();
        $this->assertContains('unblock1.js', $reqs['unblock']);
        $this->assertContains('unblock2.js', $reqs['unblock']);
    }

    public function testRequireBlockReturnsClassName(): void
    {
        // GIVEN the RequirementsHelper class
        // WHEN we call require_block
        $result = RequirementsHelper::require_block('return-test.js');

        // THEN it should return the class name
        $this->assertSame(RequirementsHelper::class, $result);
    }

    public function testRequireUnblockReturnsClassName(): void
    {
        // GIVEN the RequirementsHelper class
        // WHEN we call require_unblock
        $result = RequirementsHelper::require_unblock('return-test2.js');

        // THEN it should return the class name
        $this->assertSame(RequirementsHelper::class, $result);
    }

    public function testProcessRequirementsBlocksFiles(): void
    {
        // GIVEN a file registered for blocking
        RequirementsHelper::require_block('process-test.js');

        // WHEN we process requirements
        RequirementsHelper::process_requirements();

        // THEN the file should be blocked in the SS Requirements backend
        $blocked = \SilverStripe\View\Requirements::backend()->getBlocked();
        $this->assertContains('process-test.js', $blocked);
    }
}
