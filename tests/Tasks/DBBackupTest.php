<?php

namespace Azt3k\SS\Tests\Tasks;

use Azt3k\SS\Tasks\DBBackup;
use SilverStripe\Core\Environment;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\PolyExecution\PolyCommand;
use SilverStripe\PolyExecution\PolyOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

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

    public function testBuildDumpCommandWithPassword(): void
    {
        // GIVEN database credentials including a password
        $host = 'db.example.com';
        $user = 'admin';
        $pass = 'secret123';
        $dbName = 'my_database';
        $dumpFile = '/tmp/backup.sql';

        // WHEN we build the dump command
        $cmd = DBBackup::buildDumpCommand($host, $user, $pass, $dbName, $dumpFile);

        // THEN it should contain all escaped arguments
        $this->assertStringContainsString('mysqldump --opt', $cmd);
        $this->assertStringContainsString('-h', $cmd);
        $this->assertStringContainsString('-u', $cmd);
        $this->assertStringContainsString('-p', $cmd);
        $this->assertStringContainsString('db.example.com', $cmd);
        $this->assertStringContainsString('admin', $cmd);
        $this->assertStringContainsString('my_database', $cmd);
        $this->assertStringContainsString('/tmp/backup.sql', $cmd);
    }

    public function testBuildDumpCommandWithoutPassword(): void
    {
        // GIVEN database credentials with an empty password
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $dbName = 'test_db';
        $dumpFile = '/tmp/test.sql';

        // WHEN we build the dump command
        $cmd = DBBackup::buildDumpCommand($host, $user, $pass, $dbName, $dumpFile);

        // THEN it should NOT contain a -p argument
        $this->assertStringNotContainsString('-p\'', $cmd);
        $this->assertStringContainsString('mysqldump --opt', $cmd);
        $this->assertStringContainsString('test_db', $cmd);
    }

    public function testBuildDumpCommandEscapesShellArgs(): void
    {
        // GIVEN credentials with shell-special characters
        $host = 'localhost';
        $user = 'root';
        $pass = 'p@ss; rm -rf /';
        $dbName = 'my_db';
        $dumpFile = '/tmp/backup.sql';

        // WHEN we build the dump command
        $cmd = DBBackup::buildDumpCommand($host, $user, $pass, $dbName, $dumpFile);

        // THEN dangerous characters should be escaped (wrapped in single quotes)
        $this->assertStringContainsString("'localhost'", $cmd);
        $this->assertStringContainsString("'root'", $cmd);
        $this->assertStringContainsString("'my_db'", $cmd);
    }

    public function testRunFailsWithoutDatabaseName(): void
    {
        // GIVEN SS_DATABASE_NAME is not set
        $originalDbName = Environment::getEnv('SS_DATABASE_NAME');
        Environment::setEnv('SS_DATABASE_NAME', '');

        $task = new DBBackup();
        $input = new ArrayInput([]);
        $buffered = new BufferedOutput();
        $output = PolyOutput::create(PolyOutput::FORMAT_ANSI);
        $output->setWrappedOutput($buffered);

        try {
            // WHEN we run the task
            $result = $task->run($input, $output);

            // THEN it should return failure
            $this->assertSame(1, $result);
            $this->assertStringContainsString('SS_DATABASE_NAME', $buffered->fetch());
        } finally {
            // Restore original env
            if ($originalDbName) {
                Environment::setEnv('SS_DATABASE_NAME', $originalDbName);
            }
        }
    }
}
