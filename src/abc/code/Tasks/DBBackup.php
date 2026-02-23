<?php

namespace Azt3k\SS\Tasks;

use SilverStripe\Core\Environment;
use SilverStripe\PolyExecution\PolyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class DBBackup extends PolyCommand
{
    protected static string $commandName = 'abc:db-backup';
    protected static string $description = 'Creates a MySQL database dump';

    public function getTitle(): string
    {
        return 'DB Backup';
    }

    public function run(InputInterface $input, \SilverStripe\PolyExecution\PolyOutput $output): int
    {
        $dbHost = Environment::getEnv('SS_DATABASE_SERVER') ?: 'localhost';
        $dbUser = Environment::getEnv('SS_DATABASE_USERNAME') ?: 'root';
        $dbPass = Environment::getEnv('SS_DATABASE_PASSWORD') ?: '';
        $dbName = Environment::getEnv('SS_DATABASE_NAME');

        if (!$dbName) {
            $output->writeln('Error: SS_DATABASE_NAME environment variable is not set');
            return Command::FAILURE;
        }

        $backupFolder = dirname(__DIR__, 3) . '/db_backups';
        $dumpFile = $backupFolder . '/' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';

        if (!is_dir($backupFolder)) {
            mkdir($backupFolder, 0755, true);
        }

        $cmd = static::buildDumpCommand($dbHost, $dbUser, $dbPass, $dbName, $dumpFile);

        exec($cmd, $cmdOutput, $returnCode);

        if ($returnCode !== 0) {
            $output->writeln('Error: mysqldump failed with exit code ' . $returnCode);
            return Command::FAILURE;
        }

        $output->writeln('Created: ' . $dumpFile);

        return Command::SUCCESS;
    }

    /**
     * Build the mysqldump command string.
     */
    public static function buildDumpCommand(string $host, string $user, string $pass, string $dbName, string $dumpFile): string
    {
        $passArg = $pass !== '' ? '-p' . escapeshellarg($pass) : '';

        return sprintf(
            'mysqldump --opt -h %s -u %s %s %s > %s',
            escapeshellarg($host),
            escapeshellarg($user),
            $passArg,
            escapeshellarg($dbName),
            escapeshellarg($dumpFile)
        );
    }
}
