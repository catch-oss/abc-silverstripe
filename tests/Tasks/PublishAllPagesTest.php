<?php

namespace Azt3k\SS\Tests\Tasks;

use Azt3k\SS\Tasks\PublishAllPages;
use Page;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\PolyExecution\PolyCommand;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;

class PublishAllPagesTest extends SapphireTest
{
    protected $usesDatabase = true;

    public function testExtendsPolyCommand(): void
    {
        // GIVEN the PublishAllPages class
        // WHEN we check its inheritance
        $task = new PublishAllPages();

        // THEN it should extend PolyCommand
        $this->assertInstanceOf(PolyCommand::class, $task);
    }

    public function testGetTitleReturnsString(): void
    {
        // GIVEN the PublishAllPages task
        $task = new PublishAllPages();

        // WHEN we get the title
        $title = $task->getTitle();

        // THEN it should return a non-empty string
        $this->assertNotEmpty($title);
        $this->assertIsString($title);
    }

    public function testGetOptionsReturnsArray(): void
    {
        // GIVEN the PublishAllPages task
        $task = new PublishAllPages();

        // WHEN we get the options
        $options = $task->getOptions();

        // THEN it should return an array
        $this->assertIsArray($options);
    }

    public function testRunPublishesPages(): void
    {
        // GIVEN a page exists in the database
        $page = Page::create();
        $page->Title = 'Publish Test Page';
        $page->write();

        // WHEN we run the publish all pages command
        $task = new PublishAllPages();
        $input = new ArrayInput([]);
        $buffered = new BufferedOutput();
        $output = PolyOutput::create(PolyOutput::FORMAT_ANSI);
        $output->setWrappedOutput($buffered);
        $result = $task->run($input, $output);

        // THEN it should succeed and publish the page
        $this->assertSame(0, $result);
        $this->assertTrue($page->isPublished());
    }
}
