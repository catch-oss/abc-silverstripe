<?php

namespace Azt3k\SS\Tests\Tasks;

use Azt3k\SS\Tasks\PublishAllPages;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\PolyExecution\PolyCommand;

class PublishAllPagesTest extends SapphireTest
{
    protected $usesDatabase = false;

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
}
