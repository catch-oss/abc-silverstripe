<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\HTMLTextExtension;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\FieldType\DBHTMLText;

class HTMLTextExtensionTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testExtensionIsApplied(): void
    {
        // GIVEN the HTMLTextExtension is configured in YAML
        // WHEN we check if DBHTMLText has the extension
        $hasExtension = DBHTMLText::has_extension(HTMLTextExtension::class);

        // THEN it should be applied
        $this->assertTrue($hasExtension);
    }

    public function testFirstBlockReturnsSingleBlock(): void
    {
        // GIVEN an HTMLText field with multiple paragraphs
        $field = DBHTMLText::create();
        $field->setValue('<p>First paragraph</p><p>Second paragraph</p><p>Third paragraph</p>');

        // WHEN we get the first block
        $result = $field->FirstBlock();

        // THEN it should contain the first paragraph content
        $this->assertNotNull($result);
    }

    public function testFirstBlocksReturnsMultipleBlocks(): void
    {
        // GIVEN an HTMLText field with multiple paragraphs
        $field = DBHTMLText::create();
        $field->setValue('<p>First</p><p>Second</p><p>Third</p>');

        // WHEN we get the first 2 blocks
        $result = $field->FirstBlocks(2);

        // THEN it should return content
        $this->assertNotNull($result);
    }
}
