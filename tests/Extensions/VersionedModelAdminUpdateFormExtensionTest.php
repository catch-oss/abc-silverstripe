<?php

namespace Azt3k\SS\Tests\Extensions;

use Azt3k\SS\Extensions\VersionedModelAdminUpdateFormExtension;
use Azt3k\SS\GridField\VersionedGridFieldDetailForm;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Versioned\Versioned;

class VersionedModelAdminUpdateFormExtensionTest extends SapphireTest
{
    protected $usesDatabase = true;

    public function testOnBeforeInitSetsStageToStage(): void
    {
        // GIVEN the stage is set to Live
        Versioned::set_stage(Versioned::LIVE);
        $ext = new VersionedModelAdminUpdateFormExtension();

        // WHEN onBeforeInit is called
        $ext->onBeforeInit();

        // THEN the stage should be set to Stage
        $this->assertSame('Stage', Versioned::get_stage());
    }

    public function testUpdateEditFormSwapsComponentsForVersionedClass(): void
    {
        // GIVEN a form with a GridField backed by SiteTree (which has Versioned)
        $list = SiteTree::get();
        $config = GridFieldConfig_RecordEditor::create();
        $gridField = GridField::create('TestGrid', 'Test', $list, $config);
        $fieldList = FieldList::create($gridField);

        $form = $this->createStub(Form::class);
        $form->method('Fields')->willReturn($fieldList);

        $ext = new VersionedModelAdminUpdateFormExtension();

        // WHEN updateEditForm is called
        $ext->updateEditForm($form);

        // THEN the GridFieldDetailForm component should be a VersionedGridFieldDetailForm
        $updatedConfig = $gridField->getConfig();
        $detailForm = $updatedConfig->getComponentByType(GridFieldDetailForm::class);
        $this->assertInstanceOf(VersionedGridFieldDetailForm::class, $detailForm);
    }

    public function testUpdateEditFormRemovesDeleteAction(): void
    {
        // GIVEN a form with a GridField backed by SiteTree
        $list = SiteTree::get();
        $config = GridFieldConfig_RecordEditor::create();
        $gridField = GridField::create('TestGrid', 'Test', $list, $config);
        $fieldList = FieldList::create($gridField);

        $form = $this->createStub(Form::class);
        $form->method('Fields')->willReturn($fieldList);

        $ext = new VersionedModelAdminUpdateFormExtension();

        // WHEN updateEditForm is called
        $ext->updateEditForm($form);

        // THEN GridFieldDeleteAction should be removed
        $updatedConfig = $gridField->getConfig();
        $this->assertNull($updatedConfig->getComponentByType(GridFieldDeleteAction::class));
    }

    public function testUpdateEditFormIgnoresNonGridFields(): void
    {
        // GIVEN a form with only non-GridField fields
        $textField = \SilverStripe\Forms\TextField::create('Name', 'Name');
        $fieldList = FieldList::create($textField);

        $form = $this->createStub(Form::class);
        $form->method('Fields')->willReturn($fieldList);

        $ext = new VersionedModelAdminUpdateFormExtension();

        // WHEN updateEditForm is called
        $ext->updateEditForm($form);

        // THEN no error should occur (extension gracefully skips non-GridField fields)
        $this->assertSame('Name', $textField->getName());
    }
}
