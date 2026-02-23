<?php

namespace Azt3k\SS\Tests\Tasks;

use Azt3k\SS\Tasks\DBBackup;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\PolyExecution\PolyCommand;

class DBBackupTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testExtendsPolyCommand(): void
    {
        // GIVEN the DBBackup class
        // WHEN we check its inheritance
        $task = new DBBackup();

        // THEN it should extend PolyCommand
        $this->assertInstanceOf(PolyCommand::class, $task);
    }

    public function testGetTitleReturnsString(): void
    {
        // GIVEN the DBBackup task
        $task = new DBBackup();

        // WHEN we get the title
        $title = $task->getTitle();

        // THEN it should return a non-empty string
        $this->assertNotEmpty($title);
        $this->assertIsString($title);
    }

    public function testGetOptionsReturnsArray(): void
    {
        // GIVEN the DBBackup task
        $task = new DBBackup();

        // WHEN we get the options
        $options = $task->getOptions();

        // THEN it should return an array
        $this->assertIsArray($options);
    }
}
