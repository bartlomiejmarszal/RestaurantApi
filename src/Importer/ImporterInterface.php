<?php
namespace App\Importer;

use Symfony\Component\Console\Output\OutputInterface;

interface ImporterInterface
{
    public function import(OutputInterface $output);
}