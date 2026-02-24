<?php
namespace Azt3k\SS\Extensions;
use SilverStripe\Control\Director;
use Azt3k\SS\Classes\RequirementsHelper;
use SilverStripe\Core\Extension;

/**
 * @author AzT3K
 */
class AbcControllerExtension extends Extension {

	public function onAfterInit() {
		RequirementsHelper::process_requirements();
	}

    public function HashedPath(string $file, ?string $extension = null): string {
        $absPath = Director::getAbsFile(trim($file  . ($extension ? '.' . $extension : ''), '/'));
        return $file . '?h=' . sha1_file($absPath);
    }

    public function TimestampedPath(string $file, ?string $extension = null): string {
        $absPath = Director::getAbsFile(trim($file . ($extension ? '.' . $extension : ''), '/'));
        return $file . '?m=' . filemtime($absPath);
    }

}
