<?php

namespace Azt3k\SS\Forms;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Forms\Form;

class AbcForm extends Form
{
    /**
     * Returns a map of subclass names to human-readable labels for the given
     * form class (or this class if none specified).
     *
     * @return array<string, string> ['ClassName' => 'Class Name']
     */
    public static function getSubForms(?string $className = null): array
    {
        if (!$className) {
            $className = static::class;
        }

        $subForms = [];

        foreach (ClassInfo::subclassesFor($className, false) as $class) {
            $short = (new \ReflectionClass($class))->getShortName();
            $label = trim(preg_replace('/([A-Z])/', ' $1', $short));
            $subForms[$class] = $label;
        }

        return $subForms;
    }
}
