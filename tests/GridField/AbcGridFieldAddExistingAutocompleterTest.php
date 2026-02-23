<?php

namespace Azt3k\SS\Tests\GridField;

use Azt3k\SS\GridField\AbcGridFieldAddExistingAutocompleter;
use Page;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\ORM\DataList;

class AbcGridFieldAddExistingAutocompleterTest extends SapphireTest
{
    protected $usesDatabase = true;

    public function testDoSearchReturnsJsonForMatchingRecords(): void
    {
        // GIVEN pages with known titles and a GridField backed by Page
        $page = Page::create();
        $page->Title = 'Autocomplete Searchable Item';
        $page->write();

        $list = Page::get();
        $autocompleter = new AbcGridFieldAddExistingAutocompleter();
        $autocompleter->setSearchFields(['Title']);

        $config = GridFieldConfig::create()->addComponent($autocompleter);
        $gridField = GridField::create('TestGrid', 'Test', $list, $config);

        $request = new HTTPRequest('GET', '/', ['gridfield_relationsearch' => 'Searchable']);

        // WHEN we perform a search
        $json = $autocompleter->doSearch($gridField, $request);

        // THEN it should return valid JSON containing the matching page
        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertNotEmpty($decoded);
        // The result should contain the page's ID as a key
        $this->assertArrayHasKey($page->ID, $decoded);
    }

    public function testDoSearchReturnsEmptyForNoMatch(): void
    {
        // GIVEN a page that won't match the search
        $page = Page::create();
        $page->Title = 'No Match Page';
        $page->write();

        $list = Page::get();
        $autocompleter = new AbcGridFieldAddExistingAutocompleter();
        $autocompleter->setSearchFields(['Title']);

        $config = GridFieldConfig::create()->addComponent($autocompleter);
        $gridField = GridField::create('TestGrid', 'Test', $list, $config);

        $request = new HTTPRequest('GET', '/', ['gridfield_relationsearch' => 'xyznonexistent99']);

        // WHEN we perform a search with a term that won't match
        $json = $autocompleter->doSearch($gridField, $request);

        // THEN it should return an empty JSON object
        $decoded = json_decode($json, true);
        $this->assertIsArray($decoded);
        $this->assertEmpty($decoded);
    }

    public function testDoSearchIsCaseInsensitive(): void
    {
        // GIVEN a page with a mixed-case title
        $page = Page::create();
        $page->Title = 'CamelCase Title Here';
        $page->write();

        $list = Page::get();
        $autocompleter = new AbcGridFieldAddExistingAutocompleter();
        $autocompleter->setSearchFields(['Title']);

        $config = GridFieldConfig::create()->addComponent($autocompleter);
        $gridField = GridField::create('TestGrid', 'Test', $list, $config);

        $request = new HTTPRequest('GET', '/', ['gridfield_relationsearch' => 'camelcase']);

        // WHEN we search with lowercase
        $json = $autocompleter->doSearch($gridField, $request);

        // THEN it should still find the page (LOWER() in SQL)
        $decoded = json_decode($json, true);
        $this->assertNotEmpty($decoded);
        $this->assertArrayHasKey($page->ID, $decoded);
    }

    public function testDoSearchThrowsWithNoSearchFields(): void
    {
        // GIVEN a GridField with no searchable fields configured
        $list = Page::get();
        $autocompleter = new AbcGridFieldAddExistingAutocompleter();
        $autocompleter->setSearchFields([]);

        $config = GridFieldConfig::create()->addComponent($autocompleter);
        $gridField = GridField::create('TestGrid', 'Test', $list, $config);

        $request = new HTTPRequest('GET', '/', ['gridfield_relationsearch' => 'test']);

        // WHEN we try to search
        // THEN it should throw a LogicException
        $this->expectException(\LogicException::class);
        $autocompleter->doSearch($gridField, $request);
    }
}
