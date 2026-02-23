<?php

namespace Azt3k\SS\Tasks;

use Page;
use SilverStripe\PolyExecution\PolyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

class PublishAllPages extends PolyCommand
{
    protected static string $commandName = 'abc:publish-all-pages';
    protected static string $description = 'Publish all Pages';

    public function getTitle(): string
    {
        return 'Publish all Pages';
    }

    public function run(InputInterface $input, \SilverStripe\PolyExecution\PolyOutput $output): int
    {
        $output->writeln('running publish all pages task...');

        $pages = Page::get();
        foreach ($pages as $page) {
            $page->publishRecursive();
            $output->writeln('published ' . $page->Title);
        }

        $output->writeln('finished');

        return Command::SUCCESS;
    }
}
