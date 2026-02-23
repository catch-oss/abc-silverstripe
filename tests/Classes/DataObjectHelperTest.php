<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\DataObjectHelper;
use Page;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\DataObject;

class DataObjectHelperTest extends SapphireTest
{
    protected $usesDatabase = true;

    public function testGetTableForClassReturnsTableName(): void
    {
        // GIVEN a DataObject subclass with a known table
        $className = Page::class;

        // WHEN we get the table for it
        $table = DataObjectHelper::getTableForClass($className);

        // THEN it should return a non-empty string
        $this->assertNotEmpty($table);
        $this->assertIsString($table);
    }

    public function testGetSubclassesOfReturnsArray(): void
    {
        // GIVEN the DataObject base class
        $parent = DataObject::class;

        // WHEN we get subclasses
        $subclasses = DataObjectHelper::getSubclassesOf($parent);

        // THEN it should return an array with at least one entry
        $this->assertIsArray($subclasses);
        $this->assertNotEmpty($subclasses);
    }

    public function testDO2ArrayReturnsArray(): void
    {
        // GIVEN a Page instance written to the database
        $page = Page::create();
        $page->Title = 'Test Page';
        $page->write();

        // WHEN we convert it to an array
        $result = DataObjectHelper::DO2Array($page, 0);

        // THEN it should return an array with at least ID and Title
        $this->assertIsArray($result);
        $this->assertArrayHasKey('ID', $result);
        $this->assertSame($page->ID, $result['ID']);
    }

    public function testDO2JSONReturnsValidJSON(): void
    {
        // GIVEN a Page instance written to the database
        $page = Page::create();
        $page->Title = 'JSON Test';
        $page->write();

        // WHEN we convert to JSON
        $json = DataObjectHelper::DO2JSON($page, 0);

        // THEN it should return valid JSON with ID
        $this->assertIsString($json);
        $decoded = json_decode($json, true);
        $this->assertNotNull($decoded);
        $this->assertArrayHasKey('ID', $decoded);
    }

    public function testDOS2ArrayReturnsArrayOfArrays(): void
    {
        // GIVEN two Page instances written to the database
        $page1 = Page::create();
        $page1->Title = 'Page 1';
        $page1->write();
        $page2 = Page::create();
        $page2->Title = 'Page 2';
        $page2->write();
        $list = [$page1, $page2];

        // WHEN we convert to array
        $result = DataObjectHelper::DOS2Array($list, 0);

        // THEN it should return an array of arrays
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testDOS2JSONReturnsValidJSON(): void
    {
        // GIVEN two Page instances written to the database
        $page1 = Page::create();
        $page1->Title = 'DOS JSON 1';
        $page1->write();
        $page2 = Page::create();
        $page2->Title = 'DOS JSON 2';
        $page2->write();

        // WHEN we convert to JSON
        $json = DataObjectHelper::DOS2JSON([$page1, $page2], 0);

        // THEN it should return valid JSON array with 2 entries
        $this->assertIsString($json);
        $decoded = json_decode($json, true);
        $this->assertNotNull($decoded);
        $this->assertCount(2, $decoded);
    }

    public function testTableExistsReturnsTrueForKnownTable(): void
    {
        // GIVEN a class name that has a table in the test database
        // WHEN we check if the table exists
        $result = DataObjectHelper::tableExists('SiteTree');

        // THEN it should return true
        $this->assertTrue($result);
    }

    public function testTableExistsReturnsFalseForNonExistentTable(): void
    {
        // GIVEN a class name with no corresponding table
        // WHEN we check if the table exists
        $result = DataObjectHelper::tableExists('NonExistentTableXyz123');

        // THEN it should return false
        $this->assertFalse($result);
    }

    public function testGetTableForClassCachesResult(): void
    {
        // GIVEN we call getTableForClass twice for the same class
        $table1 = DataObjectHelper::getTableForClass(Page::class);

        // WHEN we call it again
        $table2 = DataObjectHelper::getTableForClass(Page::class);

        // THEN both calls should return the same result (second from cache)
        $this->assertSame($table1, $table2);
    }

    public function testDO2ArrayWithExclude(): void
    {
        // GIVEN a Page with a Title written to the database
        $page = Page::create();
        $page->Title = 'Exclude Test';
        $page->write();

        // WHEN we convert to array excluding the Title field
        $result = DataObjectHelper::DO2Array($page, 0, ['Title']);

        // THEN ID should be present but Title should be excluded
        $this->assertArrayHasKey('ID', $result);
        $this->assertArrayNotHasKey('Title', $result);
    }

    public function testGetSubclassesOfIncludesPage(): void
    {
        // GIVEN the SiteTree base class
        $parent = \SilverStripe\CMS\Model\SiteTree::class;

        // WHEN we get subclasses
        $subclasses = DataObjectHelper::getSubclassesOf($parent);

        // THEN it should include Page
        $this->assertContains(Page::class, $subclasses);
    }

    public function testVersionedTableReturnsTableName(): void
    {
        // GIVEN a Page class (which has Versioned extension)
        // WHEN we get the versioned table
        $table = DataObjectHelper::versioned_table(Page::class);

        // THEN it should return a non-empty string
        $this->assertNotEmpty($table);
        $this->assertIsString($table);
    }

    public function testGetExtendedClassesReturnsArrayForVersioned(): void
    {
        // GIVEN the Versioned extension is applied to SiteTree
        $extension = \SilverStripe\Versioned\Versioned::class;

        // WHEN we get classes extended by Versioned
        $classes = DataObjectHelper::getExtendedClasses($extension);

        // THEN it should return an array containing SiteTree
        $this->assertIsArray($classes);
        $this->assertNotEmpty($classes);
    }

    public function testGetExtendedClassesReturnsFalseForUnknownExtension(): void
    {
        // GIVEN a non-existent extension class name
        $extension = 'NonExistentExtension_XYZ_123';

        // WHEN we get classes extended by it
        $result = DataObjectHelper::getExtendedClasses($extension);

        // THEN it should return false
        $this->assertFalse($result);
    }

    public function testGetExtensionTablesForClassReturnsArray(): void
    {
        // GIVEN a SiteTree class
        $className = \SilverStripe\CMS\Model\SiteTree::class;

        // WHEN we get extension tables
        $tables = DataObjectHelper::getExtensionTablesForClass($className);

        // THEN it should return an array
        $this->assertIsArray($tables);
    }

    public function testDO2ArrayContainsTitleField(): void
    {
        // GIVEN a Page with a specific Title
        $page = Page::create();
        $page->Title = 'Coverage Title Test';
        $page->write();

        // WHEN we convert to array without exclusions
        $result = DataObjectHelper::DO2Array($page, 0);

        // THEN it should contain the Title
        $this->assertArrayHasKey('Title', $result);
        $this->assertSame('Coverage Title Test', $result['Title']);
    }

    public function testGetExtensionTableForClassWithPropertyReturnsFalseForMissing(): void
    {
        // GIVEN the SiteTree class
        $className = \SilverStripe\CMS\Model\SiteTree::class;

        // WHEN we look for a non-existent property
        $result = DataObjectHelper::getExtensionTableForClassWithProperty($className, 'NonExistentFieldXyz');

        // THEN it should return false
        $this->assertFalse($result);
    }

    public function testGetExtensionTablesForClassCachesResult(): void
    {
        // GIVEN we get extension tables for a class once
        $className = \SilverStripe\CMS\Model\SiteTree::class;
        $tables1 = DataObjectHelper::getExtensionTablesForClass($className);

        // WHEN we call it again
        $tables2 = DataObjectHelper::getExtensionTablesForClass($className);

        // THEN both calls should return the same result
        $this->assertSame($tables1, $tables2);
    }

    public function testDO2ArrayWithDepthZeroExcludesRelations(): void
    {
        // GIVEN a Page with a Title
        $page = Page::create();
        $page->Title = 'Depth Zero Test';
        $page->write();

        // WHEN we convert to array with depth 0
        $result = DataObjectHelper::DO2Array($page, 0);

        // THEN it should contain scalar fields but no relation arrays
        $this->assertArrayHasKey('ID', $result);
        $this->assertArrayHasKey('Title', $result);
        // With depth 0, has_many/many_many/has_one should not be traversed
        // (they would be arrays of arrays if traversed)
        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $this->fail("Depth 0 should not traverse relations, found array at key: {$key}");
            }
        }
    }

}
