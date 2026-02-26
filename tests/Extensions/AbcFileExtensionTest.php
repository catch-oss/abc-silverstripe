<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\AbcFileExtension;
use SilverStripe\Assets\File;
use SilverStripe\Dev\SapphireTest;

class AbcFileExtensionTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected static $required_extensions = [
        File::class => [AbcFileExtension::class],
    ];

    public function testExtensionIsApplied(): void
    {
        // GIVEN the AbcFileExtension is applied via $required_extensions
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

    public function testGetAllowedExtensionsContainsCommonFormats(): void
    {
        // GIVEN the AbcFileExtension class
        // WHEN we get the allowed extensions
        $extensions = AbcFileExtension::get_allowed_extensions();

        // THEN it should contain common document, image, video, and archive formats
        $this->assertContains('doc', $extensions);
        $this->assertContains('docx', $extensions);
        $this->assertContains('gif', $extensions);
        $this->assertContains('mp4', $extensions);
        $this->assertContains('zip', $extensions);
        $this->assertContains('csv', $extensions);
        $this->assertContains('xml', $extensions);
    }

    public function testGetMimeTypeSplitsOnHyphen(): void
    {
        // GIVEN an AbcFileExtension with an owner that has a known file type
        $ext = new AbcFileExtension();
        $owner = $this->createStub(File::class);
        $owner->method('getFileType')->willReturn('image-jpeg');
        $ext->setOwner($owner);

        // WHEN we get the MIME type
        $mimeType = $ext->getMimeType();

        // THEN it should return the first segment (before the hyphen)
        $this->assertSame('image', $mimeType);
    }

    public function testGetMimeTypeWithNoHyphenReturnsFullType(): void
    {
        // GIVEN an AbcFileExtension with an owner that returns a type without hyphens
        $ext = new AbcFileExtension();
        $owner = $this->createStub(File::class);
        $owner->method('getFileType')->willReturn('application');
        $ext->setOwner($owner);

        // WHEN we get the MIME type
        $mimeType = $ext->getMimeType();

        // THEN it should return the full string
        $this->assertSame('application', $mimeType);
    }

    public function testGetFileSizeDelegatesToOwner(): void
    {
        // GIVEN an AbcFileExtension with an owner that has a known size
        $ext = new AbcFileExtension();
        $owner = $this->createStub(File::class);
        $owner->method('getSize')->willReturn('1.5 KB');
        $ext->setOwner($owner);

        // WHEN we get the file size
        $size = $ext->getFileSize();

        // THEN it should return the delegated value
        $this->assertSame('1.5 KB', $size);
    }
}
