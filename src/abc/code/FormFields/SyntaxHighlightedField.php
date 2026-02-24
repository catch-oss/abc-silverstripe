<?php
namespace Azt3k\SS\FormFields;
use SilverStripe\View\Requirements;
use SilverStripe\Forms\TextareaField;
class SyntaxHighlightedField extends TextareaField {

	/**
	 * @var string $content
	 */
	protected $content;

	public function __construct(string $name, ?string $title = null, ?string $value = null, string $type = "html") {

		// Requirements
		Requirements::javascript(ABC_VENDOR_PATH . '/codemirror/lib/codemirror.js');
		Requirements::css(ABC_VENDOR_PATH . '/codemirror/lib/codemirror.css');
		Requirements::javascript(ABC_VENDOR_PATH . '/codemirror/mode/'.$type.'/'.$type.'.js');
		Requirements::javascript(ABC_PATH . '/javascript/SyntaxHighlightedField.js');

		// classes
		$this->addExtraClass('syntax-highlighted');
		$this->addExtraClass('syntax-highlighted-'.$type);
		$this->setAttribute( 'data-type', $type );

		// call parent constructor
		parent::__construct($name, $title = null, $value = null);
	}
}