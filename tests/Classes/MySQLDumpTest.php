<?php

namespace Azt3k\SS\Tests\Classes;

use Azt3k\SS\Classes\MySQLDump;
use SilverStripe\Core\Environment;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\DB;

class MySQLDumpTest extends SapphireTest
{
    protected $usesDatabase = true;

    protected function connectDump(): MySQLDump
    {
        $dump = new MySQLDump();
        $server = Environment::getEnv('SS_DATABASE_SERVER') ?: 'localhost';
        $user = Environment::getEnv('SS_DATABASE_USERNAME') ?: 'root';
        $pass = Environment::getEnv('SS_DATABASE_PASSWORD') ?: '';
        $db = DB::get_conn()->getSelectedDatabase();
        $dump->connect($server, $user, $pass, $db);

        return $dump;
    }

    /**
     * Returns an actual table name from the test database.
     */
    protected function getAnyTableName(): string
    {
        $dump = $this->connectDump();
        $dump->listTables();

        return $dump->tables[0];
    }

    public function testConnectWithValidCredentials(): void
    {
        // GIVEN a MySQLDump instance and valid DB credentials
        $dump = $this->connectDump();

        // WHEN we check the connection state
        // THEN it should be connected
        $this->assertTrue($dump->connected);
    }

    public function testConnectWithBadCredentialsFails(): void
    {
        // GIVEN a MySQLDump instance
        $dump = new MySQLDump();

        // WHEN we connect with invalid credentials
        $result = $dump->connect('localhost', 'nonexistent_user_xyz', 'bad_pass', 'no_db');

        // THEN it should fail gracefully
        $this->assertFalse($result);
        $this->assertFalse($dump->connected);
        $this->assertNotEmpty($dump->lastError);
    }

    public function testListTablesReturnsTableNames(): void
    {
        // GIVEN a connected MySQLDump instance
        $dump = $this->connectDump();

        // WHEN we list tables
        $result = $dump->listTables();

        // THEN it should return true and populate the tables array
        $this->assertTrue($result);
        $this->assertNotEmpty($dump->tables);
        $this->assertIsString($dump->tables[0]);
    }

    public function testGetTableStructureProducesDDL(): void
    {
        // GIVEN a connected MySQLDump and a known table name
        $table = $this->getAnyTableName();
        $dump = $this->connectDump();

        // WHEN we get the structure for the table
        $dump->getTableStructure($table);

        // THEN the output should contain CREATE TABLE
        $this->assertStringContainsString('CREATE TABLE', $dump->output);
        $this->assertStringContainsString($table, $dump->output);
    }

    public function testDropTableIfExistsFlag(): void
    {
        // GIVEN a connected MySQLDump with dropTableIfExists enabled
        $table = $this->getAnyTableName();
        $dump = $this->connectDump();
        $dump->dropTableIfExists = true;

        // WHEN we get the table structure
        $dump->getTableStructure($table);

        // THEN the output should include DROP TABLE IF EXISTS
        $this->assertStringContainsString('DROP TABLE IF EXISTS', $dump->output);
    }

    public function testDumpTableProducesStructureAndData(): void
    {
        // GIVEN a connected MySQLDump and a known table name
        $table = $this->getAnyTableName();
        $dump = $this->connectDump();

        // WHEN we dump the table
        $dump->dumpTable($table);

        // THEN the output should contain both structure and data sections
        $this->assertStringContainsString('Dumping structure for table', $dump->output);
        $this->assertStringContainsString('Dumping data for table', $dump->output);
    }

    public function testDumpAllDumpsMultipleTables(): void
    {
        // GIVEN a connected MySQLDump instance
        $dump = $this->connectDump();

        // WHEN we dump all tables
        $dump->dumpAll();

        // THEN the output should contain multiple CREATE TABLE statements
        $this->assertGreaterThan(
            1,
            substr_count($dump->output, 'CREATE TABLE'),
            'dumpAll should produce DDL for multiple tables'
        );
    }

    public function testListValuesProducesInsertStatements(): void
    {
        // GIVEN a connected MySQLDump and a known table
        $table = $this->getAnyTableName();
        $dump = $this->connectDump();

        // WHEN we dump data from the table
        $dump->listValues($table);

        // THEN any INSERT statements should use proper SQL syntax
        if (str_contains($dump->output, 'INSERT INTO')) {
            $this->assertMatchesRegularExpression("/INSERT INTO .+ VALUES\(/", $dump->output);
        } else {
            // Empty table is also valid
            $this->assertStringContainsString('Dumping data for table', $dump->output);
        }
    }

    public function testListTablesReturnsFalseWhenNotConnected(): void
    {
        // GIVEN a MySQLDump that is not connected
        $dump = new MySQLDump();

        // WHEN we try to list tables
        $result = $dump->listTables();

        // THEN it should return false
        $this->assertFalse($result);
    }

    public function testDumpTableContainsStructureAndDataSections(): void
    {
        // GIVEN a connected MySQLDump and a known table
        $table = $this->getAnyTableName();
        $dump = $this->connectDump();

        // WHEN we dump a single table
        $dump->dumpTable($table);

        // THEN the output should contain both structure and data markers
        $this->assertStringContainsString('Dumping structure for table', $dump->output);
        $this->assertStringContainsString('Dumping data for table', $dump->output);
        $this->assertStringContainsString($table, $dump->output);
    }
}
