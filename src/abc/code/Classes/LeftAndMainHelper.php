<?php
namespace Azt3k\SS\Classes;
use Azt3k\SS\Classes\LeftAndMainHelper;
use Azt3k\SS\Classes\RequirementsHelper;

class LeftAndMainHelper extends RequirementsHelper {
	private static $url_segment = 'process-requirements';
	// prob don't need this
	protected static $extra_requirements = array(
		'block'		=>	array(),
		'unblock'	=>	array()
	);

}