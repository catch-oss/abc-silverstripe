<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\AbcSiteTreeExtension;
use Page;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\SapphireTest;

class AbcSiteTreeExtensionTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $required_extensions = [
        SiteTree::class => [AbcSiteTreeExtension::class],
    ];

    private ?string $tempFile = null;

    protected function tearDown(): void
    {
        if ($this->tempFile && file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
        parent::tearDown();
    }

    public function testExtensionIsApplied(): void
    {
        // GIVEN the AbcSiteTreeExtension is applied via $required_extensions
        // WHEN we check if SiteTree has the extension
        $hasExtension = SiteTree::has_extension(AbcSiteTreeExtension::class);

        // THEN it should be applied
        $this->assertTrue($hasExtension);
    }

    public function testHashedPathAppendsSha1Hash(): void
    {
        // GIVEN a real file in the project base and a Page object
        $filename = 'tmp_test_' . uniqid() . '.txt';
        $absPath = BASE_PATH . '/' . $filename;
        file_put_contents($absPath, 'sitetree-hash');
        $this->tempFile = $absPath;

        $page = Page::create();
        $page->Title = 'Hash Test';
        $page->write();

        // WHEN we call HashedPath on the page
        $result = $page->HashedPath($filename);

        // THEN it should append ?h=<sha1> to the path
        $expectedHash = sha1_file($absPath);
        $this->assertSame($filename . '?h=' . $expectedHash, $result);
    }

    public function testTimestampedPathAppendsMtime(): void
    {
        // GIVEN a real file in the project base and a Page object
        $filename = 'tmp_test_' . uniqid() . '.txt';
        $absPath = BASE_PATH . '/' . $filename;
        file_put_contents($absPath, 'sitetree-timestamp');
        $this->tempFile = $absPath;

        $page = Page::create();
        $page->Title = 'Timestamp Test';
        $page->write();

        // WHEN we call TimestampedPath on the page
        $result = $page->TimestampedPath($filename);

        // THEN it should append ?m=<mtime> to the path
        $expectedMtime = filemtime($absPath);
        $this->assertSame($filename . '?m=' . $expectedMtime, $result);
    }

    public function testHashedPathWithExtensionParameter(): void
    {
        // GIVEN a file with a separate extension argument
        $filename = 'tmp_test_' . uniqid();
        $absPath = BASE_PATH . '/' . $filename . '.css';
        file_put_contents($absPath, 'body {}');
        $this->tempFile = $absPath;

        $page = Page::create();
        $page->Title = 'Ext Test';
        $page->write();

        // WHEN we call HashedPath with file and extension separately
        $result = $page->HashedPath($filename, 'css');

        // THEN it should build the correct URL with hash
        $expectedHash = sha1_file($absPath);
        $this->assertSame($filename . '?h=' . $expectedHash, $result);
    }
}
