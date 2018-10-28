<?php

namespace App\Command;

use App\Importer\ImporterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    /**
     * @var ImporterInterface
     */
    private $importer;

    public function __construct(ImporterInterface $importer)
    {
        parent::__construct();
        $this->importer = $importer;
    }

    protected function configure()
    {
        $this
            ->setName('app:import')
            ->setDescription('import data from json file to configured database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importer->import($output);
    }
}