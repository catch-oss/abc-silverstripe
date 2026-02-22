# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Changed
- Upgraded to Silverstripe 6 compatibility
- Updated PHP requirement to ^8.5
- Migrated test suite to PHPUnit 11 (55 tests)
- `PublishAllPages` and `DBBackup` rewritten as PolyCommand (replaces BuildTask)
- `DataObjectHelper::getTableForClass()` now uses schema API
- `DataObjectHelper::getFieldsForObj()` uses `DataObject::getSchema()->fieldSpecs()`
- `AbcDB` uses `Environment::getEnv()` instead of `global $databaseConfig`
- All extensions now extend `SilverStripe\Core\Extension` (was `DataExtension`/`LeftAndMainExtension`)
- Named extension keys in YAML config
- SS5 namespace renames: ArrayList, ViewableData, ValidationException, ModelData

### Added
- MIGRATION-PLAN.md documenting all changes
- phpunit.xml.dist with SS framework bootstrap
- 15 test files covering utilities, extensions, forms, grid fields, and tasks

### Removed
- `MySQLDump` class (used `mysql_*` functions removed in PHP 7)
- `AbcForm::getSubForms()` (relied on SS3 `$_CLASS_MANIFEST`)
- `AbcModule::load()` deprecated (relied on `THIRDPARTY_DIR` removed in SS6)

### Fixed
- PHP 8.5 compatibility: implicit nullable params, return type declarations, typed properties
- `AbcStr::limitCharsNoDotDot` bug (was reassigning limit variable)
- `DataObjectSearch::get_cache_time` missing return statement
- `AbcURL::buildURL` variable name typo
- `VersionedGridFieldDetailForm` ValidationException usage for SS6
