<?php

namespace Azt3k\SS\Tests\FormFields;

use Azt3k\SS\FormFields\SyntaxHighlightedField;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\View\Requirements;

class SyntaxHighlightedFieldTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testConstructorRegistersRequirements(): void
    {
        // GIVEN the SyntaxHighlightedField class
        // WHEN we create a new instance with default html type
        $field = new SyntaxHighlightedField('CodeField', 'Code');

        // THEN Requirements should have the codemirror JS and CSS registered
        $backend = Requirements::backend();
        $scripts = $backend->getJavascript();
        $styles = $backend->getCSS();

        $this->assertNotEmpty($scripts, 'Should have registered JavaScript requirements');
        $this->assertNotEmpty($styles, 'Should have registered CSS requirements');
    }

    public function testConstructorAddsExtraClasses(): void
    {
        // GIVEN the SyntaxHighlightedField class
        // WHEN we create an instance with 'html' type
        $field = new SyntaxHighlightedField('CodeField', 'Code', null, 'html');

        // THEN it should have the syntax-highlighted CSS classes
        $this->assertStringContainsString('syntax-highlighted', $field->extraClass());
        $this->assertStringContainsString('syntax-highlighted-html', $field->extraClass());
    }

    public function testConstructorSetsDataTypeAttribute(): void
    {
        // GIVEN the SyntaxHighlightedField class
        // WHEN we create an instance with 'css' type
        $field = new SyntaxHighlightedField('CodeField', 'Code', null, 'css');

        // THEN it should have the data-type attribute set
        $this->assertSame('css', $field->getAttribute('data-type'));
    }

    public function testConstructorWithJavascriptType(): void
    {
        // GIVEN the SyntaxHighlightedField class
        // WHEN we create an instance with 'javascript' type
        $field = new SyntaxHighlightedField('CodeField', 'Code', null, 'javascript');

        // THEN it should have the correct extra class and data-type
        $this->assertStringContainsString('syntax-highlighted-javascript', $field->extraClass());
        $this->assertSame('javascript', $field->getAttribute('data-type'));
    }
}
