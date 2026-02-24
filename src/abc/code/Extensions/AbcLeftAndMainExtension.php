<?php
namespace Azt3k\SS\Extensions;
use Azt3k\SS\Classes\LeftAndMainHelper;
use SilverStripe\Core\Extension;

/**
 * @author AzT3K
 */
class AbcLeftAndMainExtension extends Extension {

    private static $url_segment = 'process-requirements';

	public function onAfterInit() {
		LeftAndMainHelper::process_requirements();
	}

}
