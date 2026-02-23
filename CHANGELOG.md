# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Changed
- Upgraded to Silverstripe 6 compatibility
- Updated PHP requirement to ^8.5
- Migrated test suite to PHPUnit 11 (104 tests, 32% line coverage)
- `PublishAllPages` and `DBBackup` rewritten as PolyCommand (replaces BuildTask)
- `MySQLDump` rewritten to use PDO instead of removed `mysql_*` functions
- `AbcForm::getSubForms()` rewritten to use `ClassInfo::subclassesFor()` (was SS3 `$_CLASS_MANIFEST`)
- `DataObjectHelper::getTableForClass()` now uses schema API
- `DataObjectHelper::getFieldsForObj()` uses `DataObject::getSchema()->fieldSpecs()`
- `AbcDB` uses `Environment::getEnv()` instead of `global $databaseConfig`
- All extensions now extend `SilverStripe\Core\Extension` (was `DataExtension`/`LeftAndMainExtension`)
- Named extension keys in YAML config
- SS5 namespace renames: ArrayList, ViewableData, ValidationException, ModelData

### Added
- MIGRATION-PLAN.md documenting all changes
- phpunit.xml.dist with SS framework bootstrap
- 17 test files covering utilities, extensions, forms, grid fields, and tasks

### Deprecated
- `AbcModule::load()` — throws `RuntimeException`. The bundled jQuery 1.x / jQuery Mobile / Bootstrap / Nivo Slider scripts it loaded are obsolete. Use the Silverstripe `Requirements` API directly to load your own front-end dependencies.

### Fixed
- PHP 8.5 compatibility: implicit nullable params, return type declarations, typed properties
- `DataObjectHelper::versioned_table()` null deprecation when Versioned stage is unset
- `PublishAllPages` and `DBBackup` using undefined `self::SUCCESS`/`self::FAILURE` constants (now uses `Symfony\Command`)
- `AbcStr::limitCharsNoDotDot` bug (was reassigning limit variable)
- `DataObjectSearch::get_cache_time` missing return statement
- `AbcURL::buildURL` variable name typo
- `VersionedGridFieldDetailForm` ValidationException usage for SS6
