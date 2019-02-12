<?php
namespace Azt3k\SS\Extensions;
use Azt3k\SS\Classes\LeftAndMainHelper;
use SilverStripe\Admin\LeftAndMainExtension;

/**
 * @author AzT3K
 */
class AbcLeftAndMainExtension extends LeftAndMainExtension {

	public function onAfterInit() {
		LeftAndMainHelper::process_requirements();
	}

}
