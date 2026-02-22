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
}
