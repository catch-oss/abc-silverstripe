<?php
namespace Azt3k\SS\Extensions;
use SilverStripe\Control\Director;
use SilverStripe\Core\Extension;

/**
 * @author AzT3K
 */
class AbcSiteTreeExtension extends Extension {

    private static $indexes = array(
        'Title' => true,
        'Content'  => array(
            'type' => 'fulltext',
            'columns' => ["Content"]
        )
    );

    public function HashedPath(string $file, ?string $extension = null): string {
        $absPath = Director::getAbsFile(trim($file  . ($extension ? '.' . $extension : ''), '/'));
        return $file . '?h=' . sha1_file($absPath);
    }

    public function TimestampedPath(string $file, ?string $extension = null): string {
        $absPath = Director::getAbsFile(trim($file . ($extension ? '.' . $extension : ''), '/'));
        return $file . '?m=' . filemtime($absPath);
    }

}
