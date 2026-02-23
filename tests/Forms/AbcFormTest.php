<?php

namespace Azt3k\SS\Tests\Forms;

use Azt3k\SS\Forms\AbcForm;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\Form;

class AbcFormTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testExtendsForm(): void
    {
        // GIVEN the AbcForm class
        // WHEN we check its inheritance
        $reflection = new \ReflectionClass(AbcForm::class);

        // THEN it should extend Form
        $this->assertTrue($reflection->isSubclassOf(Form::class));
    }

    public function testGetSubFormsReturnsArray(): void
    {
        // GIVEN the AbcForm class
        // WHEN we get its subforms
        $result = AbcForm::getSubForms();

        // THEN it should return an array
        $this->assertIsArray($result);
    }

    public function testGetSubFormsWithExplicitClass(): void
    {
        // GIVEN the base Form class which has known subclasses
        // WHEN we get subforms for Form
        $result = AbcForm::getSubForms(Form::class);

        // THEN it should include AbcForm with a human-readable label
        $this->assertArrayHasKey(AbcForm::class, $result);
        $this->assertSame('Abc Form', $result[AbcForm::class]);
    }

    public function testGetSubFormsLabelsAreCamelCaseSplit(): void
    {
        // GIVEN the base Form class
        // WHEN we get subforms
        $result = AbcForm::getSubForms(Form::class);

        // THEN all labels should be non-empty strings with spaces between words
        foreach ($result as $class => $label) {
            $this->assertNotEmpty($label, "Label for {$class} should not be empty");
            $this->assertIsString($label);
        }
    }
}
