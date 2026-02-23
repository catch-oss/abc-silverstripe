<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\AbcControllerExtension;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Session;
use SilverStripe\Dev\SapphireTest;

class AbcControllerExtensionTest extends SapphireTest
{
    protected $usesDatabase = false;

    private ?string $tempFile = null;

    protected function tearDown(): void
    {
        if ($this->tempFile && file_exists($this->tempFile)) {
            unlink($this->tempFile);
        }
        parent::tearDown();
    }

    /**
     * Creates a temporary file in the project base and returns its relative path.
     */
    private function createTempFile(string $content = 'test'): string
    {
        $baseDir = BASE_PATH;
        $filename = 'tmp_test_' . uniqid() . '.txt';
        $absPath = $baseDir . '/' . $filename;
        file_put_contents($absPath, $content);
        $this->tempFile = $absPath;

        return $filename;
    }

    public function testExtensionIsApplied(): void
    {
        // GIVEN the AbcControllerExtension is configured in YAML
        // WHEN we check if Controller has the extension
        $hasExtension = Controller::has_extension(AbcControllerExtension::class);

        // THEN it should be applied
        $this->assertTrue($hasExtension);
    }

    public function testHashedPathAppendsSha1Hash(): void
    {
        // GIVEN a real file exists in the project base
        $relativePath = $this->createTempFile('hash-me');
        $expectedHash = sha1_file($this->tempFile);
        $controller = Controller::create();
        $request = new HTTPRequest('GET', '/');
        $request->setSession(new Session([]));
        $controller->setRequest($request);
        $controller->pushCurrent();

        try {
            // WHEN we call HashedPath on the controller
            $result = $controller->HashedPath($relativePath);

            // THEN it should append ?h=<sha1> to the path
            $this->assertSame($relativePath . '?h=' . $expectedHash, $result);
        } finally {
            $controller->popCurrent();
        }
    }

    public function testHashedPathWithExtensionParameter(): void
    {
        // GIVEN a file with a separate extension argument
        $baseDir = BASE_PATH;
        $filename = 'tmp_test_' . uniqid();
        $absPath = $baseDir . '/' . $filename . '.js';
        file_put_contents($absPath, 'var x = 1;');
        $this->tempFile = $absPath;
        $controller = Controller::create();
        $request = new HTTPRequest('GET', '/');
        $request->setSession(new Session([]));
        $controller->setRequest($request);
        $controller->pushCurrent();

        try {
            // WHEN we call HashedPath with file and extension separately
            $result = $controller->HashedPath($filename, 'js');

            // THEN it should build the correct URL with hash
            $expectedHash = sha1_file($absPath);
            $this->assertSame($filename . '?h=' . $expectedHash, $result);
        } finally {
            $controller->popCurrent();
        }
    }

    public function testTimestampedPathAppendsMtime(): void
    {
        // GIVEN a real file exists in the project base
        $relativePath = $this->createTempFile('timestamp-me');
        $expectedMtime = filemtime($this->tempFile);
        $controller = Controller::create();
        $request = new HTTPRequest('GET', '/');
        $request->setSession(new Session([]));
        $controller->setRequest($request);
        $controller->pushCurrent();

        try {
            // WHEN we call TimestampedPath on the controller
            $result = $controller->TimestampedPath($relativePath);

            // THEN it should append ?m=<mtime> to the path
            $this->assertSame($relativePath . '?m=' . $expectedMtime, $result);
        } finally {
            $controller->popCurrent();
        }
    }
}
