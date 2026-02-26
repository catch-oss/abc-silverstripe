ABC SilverStripe Library
========================

<!-- PROJECT SHIELDS -->
[![SonarCloud](https://github.com/catch-oss/abc-silverstripe/actions/workflows/sonar.yml/badge.svg)](https://github.com/catch-oss/abc-silverstripe/actions/workflows/sonar.yml)
[![Test](https://github.com/catch-oss/abc-silverstripe/actions/workflows/test.yml/badge.svg)](https://github.com/catch-oss/abc-silverstripe/actions/workflows/test.yml)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=catch-design_catch-oss-abc-silverstripe)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=bugs)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=code_smells)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=coverage)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Duplicated Lines Density](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=duplicated_lines_density)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=ncloc)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=reliability_rating)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=security_rating)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=sqale_index)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=sqale_rating)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=catch-design_catch-oss-abc-silverstripe&metric=vulnerabilities)](https://sonarcloud.io/component_measures?id=catch-design_catch-oss-abc-silverstripe)

## Compatibility

| Version | Silverstripe | PHP |
|---------|-------------|-----|
| release/6 | ^6.0 | ^8.5 |
| release/5 | ^5.1 | ~8.4 |

## What's in this thing anyway?

This is a base library that is required by some of the other abc modules. It
includes a few things, some of the more useful features are:

### Enhanced requirements handling

Allows for more granular inclusion of dependencies meaning you can more easily
block front end dependencies from the CMS and fixes some issues with x-include
headers in the security ping.

### Basic Utility Classes

- `AbcDB` - Zero config PDO based DB abstraction layer for when the ORM doesn't
  do what you need it to
- `MySQLDump` - Pure-PHP MySQL dump generator with programmatic control over
  which tables to dump (uses PDO)
- `DataObjectHelper` - Extracting metadata from the ORM, DataObject to
  Array/JSON conversion
- `AbcStr` / `AbcURL` - String and URL manipulation classes

### CLI Commands

- `abc:publish-all-pages` - Publish all pages recursively
- `abc:db-backup` - Database backup via mysqldump

### Extensions

Extensions are **not auto-applied**. Add the ones you need to your project's YAML config (e.g. `app/_config/extensions.yml`):

#### AbcControllerExtension

Processes front-end requirements and adds cache-busting helpers (`HashedPath`, `TimestampedPath`) to all controllers.

```yaml
SilverStripe\Control\Controller:
  extensions:
    abc_controller: Azt3k\SS\Extensions\AbcControllerExtension
```

#### AbcSiteTreeExtension

Adds `HashedPath`/`TimestampedPath` cache-busting helpers and fulltext search indexes to SiteTree.

```yaml
SilverStripe\CMS\Model\SiteTree:
  extensions:
    abc_site_tree: Azt3k\SS\Extensions\AbcSiteTreeExtension
```

#### AbcImageExtension

Adds `CapturedBy`, `Location`, `DateCaptured` fields and absolute URL helpers to images.

```yaml
SilverStripe\Assets\Image:
  extensions:
    abc_image: Azt3k\SS\Extensions\AbcImageExtension
```

#### AbcFileExtension

Adds `getMimeType()` and `getFileSize()` helpers to files.

```yaml
SilverStripe\Assets\File:
  extensions:
    abc_file: Azt3k\SS\Extensions\AbcFileExtension
```

#### AbcLeftAndMainExtension

Processes CMS requirements on init.

```yaml
SilverStripe\Admin\LeftAndMain:
  extensions:
    abc_left_and_main: Azt3k\SS\Extensions\AbcLeftAndMainExtension
```

#### AbcSecurityExtension

Processes requirements on the security ping action.

```yaml
SilverStripe\Security\Security:
  extensions:
    abc_security: Azt3k\SS\Extensions\AbcSecurityExtension
```

#### VersionedModelAdminUpdateFormExtension

Replaces `GridFieldDetailForm` with a versioned variant in ModelAdmin.

```yaml
SilverStripe\Admin\ModelAdmin:
  extensions:
    abc_versioned_model_admin: Azt3k\SS\Extensions\VersionedModelAdminUpdateFormExtension
```

#### HTMLTextExtension

Adds `FirstBlock()` and `FirstBlocks()` template helpers to `DBHTMLText`.

```yaml
SilverStripe\ORM\FieldType\DBHTMLText:
  extensions:
    abc_html_text: Azt3k\SS\Extensions\HTMLTextExtension
```

### Form Fields

- SyntaxHighlightedField - extends a basic text area with syntax highlighting
- ColourPickerField
- AbcForm - base form class with subclass discovery via `getSubForms()`

## License

BSD-3-Clause-Clear. See [LICENSE](LICENSE) or the `composer.json` for details.
