<?php

use Azt3k\SS\Admin\AbcModelAdmin;
use Azt3k\SS\Extensions\AbcImageExtension;
use SilverStripe\Admin\CMSMenu;

// Define path constant
$path = str_replace('\\', '/', __DIR__);
$path_fragments = explode('/', $path);
$dir_name = $path_fragments[count($path_fragments) - 1];
define('ABC_VENDOR_PATH', $dir_name . '/thirdparty');
define('ABC_PATH', $dir_name . '/src/abc');

// Configure Image Extension
AbcImageExtension::$fallback_image = ABC_PATH . '/images/no-image.jpg';

// DatePicker config
//Object::useCustomClass('DateField_View_JQuery', 'jQueryUIDateField_View');


// remove the abc model admin from the side bar as it can't be used
// without direct managed models, and it seems this class is 
// designed to be extended, not used directly
CMSMenu::remove_menu_class(AbcModelAdmin::class);
