<?php
namespace Azt3k\SS\GridField;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridField;

class AbcGridFieldDetailForm extends GridFieldDetailForm {
	
}

class AbcGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest {

	function ItemEditForm() {
		$form = parent::ItemEditForm();
		if ($this->record->getCMSActions() && $this->record->getCMSActions()->count()) $form->setActions($this->record->getCMSActions());
		return $form;
	}

}
