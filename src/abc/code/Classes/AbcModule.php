<?php

namespace Azt3k\SS\Classes;

/**
 * @deprecated This class used SS3-era THIRDPARTY_DIR and bundled jQuery 1.x.
 * It is no longer functional in SS6. Use Requirements API directly instead.
 */
class AbcModule
{
    public static function load(string $name): void
    {
        throw new \RuntimeException(
            'AbcModule::load() is no longer supported. Use SilverStripe\View\Requirements API directly.'
        );
    }

    public static function combine(): void
    {
    }
}
