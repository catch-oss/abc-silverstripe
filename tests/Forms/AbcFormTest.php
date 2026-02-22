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
}
