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

    public function testFirstBlockReturnsEmptyForEmptyContent(): void
    {
        // GIVEN an HTMLText field with empty content
        $field = DBHTMLText::create();
        $field->setValue('');

        // WHEN we get the first block
        $result = $field->FirstBlock();

        // THEN it should return empty string
        $this->assertSame('', $result);
    }

    public function testFirstBlockContainsFirstParagraph(): void
    {
        // GIVEN an HTMLText field with identifiable paragraphs
        $field = DBHTMLText::create();
        $field->setValue('<p>Alpha content</p><p>Beta content</p>');

        // WHEN we get the first block
        $result = $field->FirstBlock();

        // THEN it should contain the first paragraph but not the second
        $html = $result->forTemplate();
        $this->assertStringContainsString('Alpha content', $html);
        $this->assertStringNotContainsString('Beta content', $html);
    }

    public function testFirstBlocksRespectsCount(): void
    {
        // GIVEN an HTMLText field with three paragraphs
        $field = DBHTMLText::create();
        $field->setValue('<p>One</p><p>Two</p><p>Three</p>');

        // WHEN we get the first 2 blocks
        $result = $field->FirstBlocks(2);

        // THEN it should contain the first two but not the third
        $html = $result->forTemplate();
        $this->assertStringContainsString('One', $html);
        $this->assertStringContainsString('Two', $html);
        $this->assertStringNotContainsString('Three', $html);
    }

    public function testFirstBlockReturnsDBHTMLText(): void
    {
        // GIVEN an HTMLText field with content
        $field = DBHTMLText::create();
        $field->setValue('<p>Test</p>');

        // WHEN we get the first block
        $result = $field->FirstBlock();

        // THEN it should return a DBHTMLText instance
        $this->assertInstanceOf(DBHTMLText::class, $result);
    }
}
