<?php

namespace Azt3k\SS\Classes;

use PDO;

/**
 * Pure-PHP MySQL dump generator.
 *
 * Provides programmatic control over which tables to dump and returns
 * the SQL output as a string. Uses PDO (via AbcDB) instead of the
 * removed mysql_* extension.
 */
class MySQLDump
{
    /** @var list<string> */
    public array $tables = [];

    public bool $connected = false;

    public string $output = '';

    public bool $dropTableIfExists = false;

    public string $lastError = '';

    protected ?PDO $pdo = null;

    public function connect(
        ?string $host = null,
        ?string $user = null,
        ?string $pass = null,
        ?string $db = null
    ): bool {
        try {
            if ($host !== null && $db !== null) {
                $dsn = 'mysql:host=' . $host . ';dbname=' . $db . ';charset=utf8mb4';
                $this->pdo = new PDO($dsn, $user ?? 'root', $pass ?? '', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } else {
                $this->pdo = AbcDB::getInstance();
            }
            $this->connected = true;
            return true;
        } catch (\PDOException $e) {
            $this->lastError = $e->getMessage();
            $this->connected = false;
            return false;
        }
    }

    public function listTables(): bool
    {
        if (!$this->connected || !$this->pdo) {
            return false;
        }

        $this->tables = [];
        $stmt = $this->pdo->query('SHOW TABLES');

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $this->tables[] = $row[0];
        }

        return true;
    }

    public function getTableStructure(string $tableName): void
    {
        $this->output .= "\n\n-- Dumping structure for table: {$tableName}\n\n";

        if ($this->dropTableIfExists) {
            $this->output .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
        }

        // Use SHOW CREATE TABLE for a faithful DDL reproduction
        $stmt = $this->pdo->query(
            'SHOW CREATE TABLE ' . $this->quoteIdentifier($tableName)
        );
        $row = $stmt->fetch(PDO::FETCH_NUM);

        if ($row) {
            $this->output .= $row[1] . ";\n";
        } else {
            $this->output .= "-- Unable to get structure for table: {$tableName}\n";
        }
    }

    public function listValues(string $tableName): void
    {
        $quoted = $this->quoteIdentifier($tableName);
        $stmt = $this->pdo->query("SELECT * FROM {$quoted}");

        if (!$stmt) {
            $this->output .= "\n\n-- Unable to get data for table: {$tableName}\n\n";
            return;
        }

        $this->output .= "\n\n-- Dumping data for table: {$tableName}\n\n";
        $columnCount = $stmt->columnCount();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $values = [];
            for ($i = 0; $i < $columnCount; $i++) {
                if ($row[$i] === null) {
                    $values[] = 'NULL';
                } elseif (is_numeric($row[$i]) && !str_contains((string) $row[$i], 'e')) {
                    $values[] = $row[$i];
                } else {
                    $values[] = $this->pdo->quote($row[$i]);
                }
            }
            $this->output .= "INSERT INTO `{$tableName}` VALUES(" . implode(', ', $values) . ");\n";
        }
    }

    public function dumpTable(string $tableName): void
    {
        $this->output = '';
        $this->getTableStructure($tableName);
        $this->listValues($tableName);
    }

    public function dumpAll(): void
    {
        $this->output = '';
        $this->listTables();

        foreach ($this->tables as $table) {
            $this->getTableStructure($table);
            $this->listValues($table);
        }
    }

    protected function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
