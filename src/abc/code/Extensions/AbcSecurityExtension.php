<?php
namespace Azt3k\SS\Extensions;
use Azt3k\SS\Classes\LeftAndMainHelper;
use SilverStripe\Core\Extension;
/**
 * @author AzT3K
 */
class AbcSecurityExtension extends Extension {

	public function onAfterInit() {

		$controller		= $this->owner;
		$params			= (object) $controller->getURLParams();

		if ($params->Action == 'ping') LeftAndMainHelper::process_requirements();

	}

}
