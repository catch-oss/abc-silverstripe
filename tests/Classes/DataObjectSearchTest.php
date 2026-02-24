<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\DataObjectSearch;
use Page;
use SilverStripe\Dev\SapphireTest;

class DataObjectSearchTest extends SapphireTest
{
    protected $usesDatabase = true;

    public function testStrToTermsFiltersBlacklistedWords(): void
    {
        // GIVEN a string containing blacklisted words (the, and, of)
        $input = 'the quick brown fox and the lazy dog of doom';

        // WHEN we convert to search terms
        $terms = DataObjectSearch::str_to_terms($input);

        // THEN blacklisted words should be filtered out
        $this->assertNotContains('the', $terms);
        $this->assertNotContains('and', $terms);
        $this->assertNotContains('of', $terms);
        $this->assertContains('quick', $terms);
        $this->assertContains('brown', $terms);
        $this->assertContains('fox', $terms);
    }

    public function testStrToTermsTrimsWhitespace(): void
    {
        // GIVEN a string with extra whitespace
        $input = 'hello world';

        // WHEN we convert to search terms
        $terms = DataObjectSearch::str_to_terms($input);

        // THEN terms should be trimmed
        $this->assertContains('hello', $terms);
        $this->assertContains('world', $terms);
    }

    public function testStrToTermsStripsPunctuation(): void
    {
        // GIVEN a string with punctuation
        $input = 'hello, world! great.';

        // WHEN we convert to search terms
        $terms = DataObjectSearch::str_to_terms($input);

        // THEN punctuation should be stripped from terms
        $this->assertContains('hello', $terms);
        $this->assertContains('world', $terms);
        $this->assertContains('great', $terms);
    }

    public function testStrToFragmentsReturnsUniqueFragments(): void
    {
        // GIVEN a multi-word string
        $input = 'one two three four five';

        // WHEN we convert to fragments
        $fragments = DataObjectSearch::str_to_fragments($input);

        // THEN it should contain individual words and multi-word fragments
        $this->assertContains('one', $fragments);
        $this->assertContains('two', $fragments);
        $this->assertContains('three', $fragments);
        // Should also contain fragments like "one two three"
        $this->assertTrue(count($fragments) > 5);
        // All values should be unique
        $this->assertSame(count($fragments), count(array_unique($fragments)));
    }

    public function testSetAndGetCacheTime(): void
    {
        // GIVEN no cache time is set
        // WHEN we set a cache time
        DataObjectSearch::set_cache_time(3600);

        // THEN we should get back the same value
        $this->assertSame(3600, DataObjectSearch::get_cache_time());

        // Cleanup
        DataObjectSearch::set_cache_time(null);
    }

    public function testFlushDoesNotThrow(): void
    {
        // GIVEN the DataObjectSearch class
        // WHEN we call flush
        DataObjectSearch::flush();

        // THEN no exception should be thrown
        $this->assertTrue(true);
    }

    public function testStrToTermsWithEmptyString(): void
    {
        // GIVEN an empty string
        $input = '';

        // WHEN we convert to search terms
        $terms = DataObjectSearch::str_to_terms($input);

        // THEN it should return an array
        $this->assertIsArray($terms);
    }

    public function testStrToTermsFiltersCaseInsensitive(): void
    {
        // GIVEN a string with capitalised blacklisted words
        $input = 'The Quick And The Lazy';

        // WHEN we convert to search terms
        $terms = DataObjectSearch::str_to_terms($input);

        // THEN blacklisted words should be filtered regardless of case
        $lowered = array_map('strtolower', $terms);
        $this->assertNotContains('the', $lowered);
        $this->assertNotContains('and', $lowered);
        $this->assertContains('quick', $lowered);
        $this->assertContains('lazy', $lowered);
    }

    public function testStrToFragmentsSingleWord(): void
    {
        // GIVEN a single word
        $input = 'hello';

        // WHEN we convert to fragments
        $fragments = DataObjectSearch::str_to_fragments($input);

        // THEN it should return an array containing the word
        $this->assertIsArray($fragments);
        $this->assertContains('hello', $fragments);
    }

    public function testStrToFragmentsTwoWords(): void
    {
        // GIVEN two words
        $input = 'hello world';

        // WHEN we convert to fragments
        $fragments = DataObjectSearch::str_to_fragments($input);

        // THEN it should return fragments including both individual words
        $this->assertContains('hello', $fragments);
        $this->assertContains('world', $fragments);
    }

    public function testSearchListReturnsDataList(): void
    {
        // GIVEN pages with known titles in the database
        $page1 = Page::create();
        $page1->Title = 'Silverstripe Migration Guide';
        $page1->write();
        $page2 = Page::create();
        $page2->Title = 'Unrelated Content Page';
        $page2->write();

        // WHEN we search for "Migration" in the Title field
        $result = DataObjectSearch::search_list(Page::class, 'Migration', ['Title']);

        // THEN it should return a DataList containing the matching page
        $this->assertGreaterThanOrEqual(1, $result->count());
        $titles = $result->column('Title');
        $this->assertContains('Silverstripe Migration Guide', $titles);
    }

    public function testSearchListSearchesMultipleFields(): void
    {
        // GIVEN a page with a known title and content
        $page = Page::create();
        $page->Title = 'Alpha Page';
        $page->Content = 'This page contains Zebra keyword';
        $page->write();

        // WHEN we search for "Zebra" across Title and Content fields
        $result = DataObjectSearch::search_list(Page::class, 'Zebra', ['Title', 'Content']);

        // THEN it should find the page via Content match
        $this->assertGreaterThanOrEqual(1, $result->count());
        $titles = $result->column('Title');
        $this->assertContains('Alpha Page', $titles);
    }

    public function testSearchListReturnsEmptyForNoMatch(): void
    {
        // GIVEN a page that doesn't match the search
        $page = Page::create();
        $page->Title = 'Completely Normal Page';
        $page->write();

        // WHEN we search for a term that won't match
        $result = DataObjectSearch::search_list(Page::class, 'xyznonexistent99', ['Title']);

        // THEN it should return an empty list
        $this->assertSame(0, $result->count());
    }

    public function testSearchListIncludesFragmentMatches(): void
    {
        // GIVEN a page with a multi-word title
        $page = Page::create();
        $page->Title = 'Advanced Search Functionality Demo';
        $page->write();

        // WHEN we search with a partial phrase that generates fragments
        $result = DataObjectSearch::search_list(Page::class, 'Search Functionality', ['Title']);

        // THEN it should match via the fragment
        $this->assertGreaterThanOrEqual(1, $result->count());
        $titles = $result->column('Title');
        $this->assertContains('Advanced Search Functionality Demo', $titles);
    }
}
