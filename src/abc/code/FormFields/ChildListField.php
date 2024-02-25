<?php
namespace Azt3k\SS\FormFields;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\DataObject;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\View\SSViewer;
use SilverStripe\Forms\LiteralField;
use Azt3k\SS\Classes\AbcPaginator;
use SilverStripe\Control\Controller;

/**
 * This field lets you put an arbitrary piece of HTML into your forms.
 *
 * <b>Usage</b>
 *
 * <code>
 * new LiteralField (
 *    $name = "literalfield",
 *    $content = '<b>some bold text</b> and <a href="http://silverstripe.com">a link</a>'
 * )
 * </code>
 *
 * @package forms
 * @subpackage fields-dataless
 */
class ChildListField extends LiteralField {

	/**
	 * @var string $content
	 */
	protected $content;

	function __construct(Controller $controller, $name, $class = 'Page', $limit = 30) {

		Requirements::javascript(ABC_PATH . '/javascript/child-list.js');
		Requirements::css(ABC_PATH . '/css/child-list.css');

		$do 			= new DataObject;
		$do->DataSet 	= AbcPaginator::get($limit)->fetch($class, "SiteTree.ParentID = ".$controller->ID, "PublicationDate DESC, Created DESC");
		$do->Paginator 	= $do->DataSet->Paginator->dataForTemplate(null,null,'/admin/getitem?ID='.$controller->ID);
		$parser			= SSViewer::fromString(SSViewer::getTemplateContent( 'ChildList' ));
		$str 			= $parser->process($do);

		parent::__construct($name, $str);

	}
}

?>