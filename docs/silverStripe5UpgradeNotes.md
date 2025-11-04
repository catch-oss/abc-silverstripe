# following code changes, for removiming classes/methods that are not imported, or even exist

in src/abc/code/Admin/AbcModelAdmin.php
changed
AddGridFieldConfig_RecordEditor
to
GridFieldConfig_RecordEditor
must have been a typo?





in src/abc/code/Classes/AbcModule.php

// @todo: this call was here pre ss4, that function is no longer there.
// but what is all this stuff doing, loading jquery, does the above not do that?
// LeftAndMain::require_javascript(THIRDPARTY_DIR."/jquery/jquery.js");





in vendor/azt3k/abc-silverstripe/src/abc/code/Classes/AbcPaginator.php

from
public static function getUnlimitedRowCount($callerClass, $filter = "", $join = ""){

    	// Init some vars
    	$oTable = $table = DataObjectHelper::getTableForClass($callerClass);
    	if (Injector::inst()->has_extension($callerClass,'Versioned')) {
    		$stage = Versioned::current_stage();
    		$table = $oTable.($stage == 'Live' ? '_'.$stage : '');
    	}

to
public static function getUnlimitedRowCount($callerClass, $filter = "", $join = "")
{

    	// Init some vars
    	$oTable = $table = DataObjectHelper::getTableForClass($callerClass);
    	if (Injector::inst()->get($callerClass)->has_extension('Versioned')) {
    		$stage = Versioned::get_stage();
    		$table = $oTable . ($stage == 'Live' ? '_' . $stage : '');
    	}
    	$wSQL = "";
    	$databaseName = Environment::getEnv('SS_DATABASE_NAME');
    	//$sql = "SELECT COUNT(*) as total FROM ".$table;
    	$sql = "SELECT COUNT(*) as total FROM " . $databaseName . '.' . $table;



in vendor/azt3k/abc-silverstripe/src/abc/code/Classes/DataObjectSearch.php
the flush function does nothing





vendor/azt3k/abc-silverstripe/src/abc/code/Classes/MySQLDump.php
this class will not work, as it uses removed php functions (mysql_connect)



in vendor/azt3k/abc-silverstripe/src/abc/code/Extensions/AbcImageExtension.php
changed getCMSFields to UpdateCMSFields
this wouldn't have been working since SS2 I believe. Double check if effects IOD



in vendor/azt3k/abc-silverstripe/src/abc/code/FormFields/ChildListField.php
this won't work, probs hasn't since ss2, fix this or remove it


/src/abc/code/Forms/GridField/AbcGridFieldAddExistingAutocompleter.php:43
from 
		return Convert:: array2json($json);
to 
 return json_encode($json); 




 in 
 from
 code/Forms/GridField/VersionedGridFieldDetailForm.php

        $origStage = Versioned::current_stage();
        Versioned::reading_stage('Live');
 to
 Versioned::set_reading_mode('Live');




 src/abc/code/Tasks/DBBackup.php:27
 from 
 		Director::set_environment_type("dev");
to
		        /** @var Kernel $kernel */
				$kernel = Injector::inst()->get(Kernel::class);
				return $kernel->setEnvironment('dev');




