# Migration Plan: abc-silverstripe

## Summary

- **Package**: azt3k/abc-silverstripe
- **Type**: B (Silverstripe module)
- **Tier**: 4
- **Risk Level**: High
- **Estimated Scope**: 32 source files, ~35 classes, 5 templates, 1 YAML config
- **Current State**: No phpunit.xml, no .gitignore, 1 stub test file (unusable), no logging

## Change Inventory

### Namespace Renames Required

| Old Namespace | New Namespace | Files Affected |
|---|---|---|
| `SilverStripe\ORM\DataExtension` | `SilverStripe\Core\Extension` | 3: AbcFileExtension, AbcImageExtension, AbcSiteTreeExtension |
| `SilverStripe\ORM\ArrayList` | `SilverStripe\Model\List\ArrayList` | 4: AbcModelAdmin, AbcPaginator, DataObjectSearch, AbcGridFieldConfig |
| `SilverStripe\ORM\ValidationException` | `SilverStripe\Core\Validation\ValidationException` | 1: VersionedGridFieldDetailForm |
| `SilverStripe\View\ViewableData` | `SilverStripe\Model\ModelData` | 1: AbcPaginator |
| `SilverStripe\Dev\BuildTask` | `SilverStripe\PolyExecution\PolyCommand` | 2: DBBackup, PublishAllPages |

### Composer Dependency Changes

| Package | Current Version | Target Version |
|---|---|---|
| php | ^8.1 | ^8.5 |
| silverstripe/framework | ^5 | ^6.0 |
| silverstripe/cms | ^5 | ^6.0 |
| silverstripe/crontask | ^3.0 | ^4.0 (or remove if deprecated) |
| silverstripe/vendor-plugin _(new)_ | - | ^3.0 |
| composer/installers | ^2.2 | ^2.2 (keep) |
| phpunit/phpunit _(new, dev)_ | - | ^11.0 |
| silverstripe/recipe-cms _(new, dev)_ | - | ^6.0 |

### API Changes Required

| Pattern | Migration | Files Affected |
|---|---|---|
| `BuildTask` → `PolyCommand` | Rewrite class structure, `run($request)` → `run(InputInterface, PolyOutput): int` | DBBackup, PublishAllPages |
| `DataExtension` → `Extension` | Change use statement + extends | AbcFileExtension, AbcImageExtension, AbcSiteTreeExtension |
| `ViewableData` → `ModelData` | Change use + extends | AbcPaginator |
| `mysql_*` functions | Already dead code (removed PHP 7+) — MySQLDump class is non-functional | MySQLDump, DBBackup |
| `DataObjectSet` reference | Remove dead branch — `DataObjectSet` removed in SS4 | DataObjectHelper:272 |
| `$_CLASS_MANIFEST` | Remove dead code — removed in SS4 | AbcForm:11-34 |
| `THIRDPARTY_DIR` constant | Remove/replace — SS3-era constant | AbcModule:15 |
| `Object::useCustomClass()` | Already commented out — remove | _config.php:18 |
| Unnamed YAML extension keys | Add named keys (SS6 requirement) | _config/config.yml (all 8 entries) |
| `ValidationException::getResult()->getMessages()` | Verify still valid in SS6 | VersionedGridFieldDetailForm:288 |
| `DataObject::get('Page')` | Change to `Page::get()` | PublishAllPages:21 |

### PHP 8.5 Compatibility Fixes

| Issue | Fix | Files Affected |
|---|---|---|
| Implicit nullable params (untyped) | Add `?` prefix or type declarations | AbcModelAdmin, AbcPaginator, DataObjectSearch, AbcStr, AbcURL, SyntaxHighlightedField, ColourPickerField, ChildPageGridFieldDetailForm, AbcForm, AbcGridFieldConfig, VersionedGridFieldDetailForm, AbcControllerExtension, AbcSiteTreeExtension (~25 instances across 13 files) |
| Missing return type declarations | Add `: void`, `: mixed`, `: static`, etc. | Most classes — especially `setUp()` methods and public APIs |

### PHPUnit Migration

| Issue | Fix | Files Affected |
|---|---|---|
| Existing test is a stub | Rewrite from scratch with real tests | tests/ABCSilverStripeTest.php |
| Uses `add_extension()` | Use YAML config or `$required_extensions` | tests/ABCSilverStripeTest.php |
| `@depends` annotation | Convert to `#[Depends]` attribute | tests/ABCSilverStripeTest.php |
| Missing namespace | Add proper namespace | tests/ABCSilverStripeTest.php |
| No phpunit.xml.dist | Create with SS framework bootstrap | _(new)_ |

### Config Changes

| File | Change Required |
|---|---|
| _config.php | Remove commented `Object::useCustomClass` line; verify `CMSMenu::remove_menu_class()` still works |
| _config/config.yml | Add named keys to all 8 extension entries |

## Dead Code Assessment

Several classes contain code that has been non-functional since PHP 7 or SS4:

| Class | Issue | Recommendation |
|---|---|---|
| MySQLDump | Uses `mysql_*` functions (removed PHP 7) | Remove entirely or rewrite with PDO |
| AbcForm::getSubForms() | References `$_CLASS_MANIFEST` (removed SS3) | Remove method |
| AbcModule::load() | References `THIRDPARTY_DIR` (SS3-era) | Remove or rewrite |
| AbcDB | Uses `global $databaseConfig` (SS3 pattern) | Rewrite to use `Environment::getEnv()` |
| DataObjectHelper:272 | Checks for `DataObjectSet` (removed SS4) | Remove dead branch |

## Risk Assessment

| Area | Risk | Notes |
|---|---|---|
| Namespace renames | Low | 5 patterns, straightforward search-replace |
| DataExtension → Extension | Low | 3 files, simple rename |
| BuildTask → PolyCommand | Medium | 2 tasks, DBBackup depends on broken MySQLDump |
| Dead code cleanup | Medium | MySQLDump, AbcForm, AbcModule have non-functional code |
| PHP 8.5 compat | Low | ~25 implicit nullable params, mechanical fixes |
| Test creation | High | Only a stub test exists — need real test suite from scratch for 80% coverage across 32 files |
| Config changes | Low | Named YAML keys, minor _config.php cleanup |
| GridField forms | Medium | Complex inheritance chain (VersionedGridFieldDetailForm) |
| AbcPaginator | Medium | Extends ViewableData (→ ModelData), sets dynamic properties on DataObjects |

## Migration Steps (Ordered)

### Phase 1: composer.json

- [ ] Update `php` to `^8.5`
- [ ] Update `silverstripe/framework` to `^6.0`
- [ ] Update `silverstripe/cms` to `^6.0`
- [ ] Check `silverstripe/crontask` SS6 compatibility (update or remove)
- [ ] Add `silverstripe/vendor-plugin: ^3.0` to require
- [ ] Add require-dev: `phpunit/phpunit: ^11.0`, `silverstripe/recipe-cms: ^6.0`
- [ ] Add autoload-dev PSR-4 for tests namespace + classmap for Page/PageController
- [ ] Add `silverstripe/recipe-plugin: true` to allow-plugins
- [ ] Run `composer validate`

### Phase 2: Namespace Renames

- [ ] `SilverStripe\ORM\DataExtension` → `SilverStripe\Core\Extension` (3 files)
- [ ] `SilverStripe\ORM\ArrayList` → `SilverStripe\Model\List\ArrayList` (4 files)
- [ ] `SilverStripe\ORM\ValidationException` → `SilverStripe\Core\Validation\ValidationException` (1 file)
- [ ] `SilverStripe\View\ViewableData` → `SilverStripe\Model\ModelData` (1 file)
- [ ] `SilverStripe\Dev\BuildTask` → `SilverStripe\PolyExecution\PolyCommand` (2 files)
- [ ] Verify no old namespaces remain via grep

### Phase 3: API Changes

- [ ] Migrate DBBackup from BuildTask to PolyCommand (`run($request)` → `run(InputInterface, PolyOutput): int`)
- [ ] Migrate PublishAllPages from BuildTask to PolyCommand
- [ ] Fix `DataObject::get('Page')` → `Page::get()` in PublishAllPages
- [ ] Remove `DataObjectSet` dead branch in DataObjectHelper:272
- [ ] Remove `$_CLASS_MANIFEST` dead code in AbcForm:11-34
- [ ] Remove/update `THIRDPARTY_DIR` in AbcModule:15
- [ ] Update AbcDB to use `Environment::getEnv()` instead of `global $databaseConfig`
- [ ] Decide: remove MySQLDump entirely (broken since PHP 7) or rewrite with PDO
- [ ] Verify `SSViewer::getTemplateContent()` exists in SS6 (ChildListField:41)
- [ ] Verify `SSViewer::fromString()` in SS6 (AbcGridFieldAddExistingAutocompleter:40)

### Phase 4: PHP 8.5 Compatibility

- [ ] Fix ~25 implicit nullable parameters across 13 files
- [ ] Add return type declarations to public methods
- [ ] Remove `${var}` interpolation if found (none found in scan)

### Phase 5: Logging Integration

- [ ] Minimal logging opportunity — most classes are utility/extension code
- [ ] Add Monolog integration if warranted (DBBackup task would benefit)

### Phase 6: Config Updates

- [ ] Add named keys to all 8 extension entries in _config/config.yml
- [ ] Remove commented `Object::useCustomClass` line from _config.php
- [ ] Verify `CMSMenu::remove_menu_class()` still works in SS6

### Phase 7: Test Suite (Silverstripe Best Practices)

- [ ] Create `phpunit.xml.dist` with bootstrap `vendor/silverstripe/framework/tests/bootstrap.php`
- [ ] Add `silverstripe/recipe-cms: ^6.0` to require-dev
- [ ] Add `silverstripe/recipe-plugin: true` to allow-plugins
- [ ] Add autoload-dev classmap for `app/src/Page.php`, `app/src/PageController.php`
- [ ] Create `.gitignore`: `vendor/`, `app/`, `public/`, `.htaccess`, `index.php`, `web.config`, `.phpunit.cache/`, `composer.lock`
- [ ] Rewrite `tests/ABCSilverStripeTest.php` — current stub is unusable
- [ ] Create tests for Extensions (SapphireTest, verify extension applies)
- [ ] Create tests for FormFields (SapphireTest, verify rendering)
- [ ] Create tests for GridField components (SapphireTest with DB)
- [ ] Create tests for utility classes (AbcStr, AbcURL — can use plain TestCase)
- [ ] Create tests for AbcPaginator (SapphireTest, needs DB for DataObject queries)
- [ ] All tests use GIVEN/WHEN/THEN comments
- [ ] Use `Page::create()` not `SiteTree::create()` convention
- [ ] Target 80% line coverage minimum
- [ ] PHPUnit 11 syntax throughout

## Dependencies

- **Depends on**:
  - `azt3k/silverstripe-twig` (Tier 3) — DONE (PR merged)
  - `silverstripe/framework` ^6.0 — available
  - `silverstripe/cms` ^6.0 — available
- **Blocks**:
  - `azt3k/abc-silverstripe-taggable` (Tier 5) — depends on this
  - `azt3k/abc-silverstripe-social` (Tier 6) — depends on this
  - `catch/ss-seo` (Tier 7) — depends on this
