<?php

namespace UtilityCli\Command\Example;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Greetings extends Command
{
    protected function configure()
    {
        $this->setName('example:greetings');
        $this->setDescription('Greetings to you from the application');
        $this->setHelp('A simple command speaks itself.');
        $this->addArgument('name', InputArgument::REQUIRED, 'Your name');
        $this->addOption('long', null, InputOption::VALUE_NONE, 'Use long greetings');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->hasOption('long') && $input->getOption('long')) {
            $hour = date('H', time());
            if ($hour <= 12) {
                $time = 'Morning';
            } else if ($hour > 12 && $hour <= 18) {
                $time = 'Afternoon';
            } else {
                $time = 'Evening';
            }
            $greeting = 'Good ' . $time . ' ' . $input->getArgument('name') . '. How are you?';
        } else {
            $greeting = 'Hello ' . $input->getArgument('name');
        }
        $output->writeln($greeting);
    }
}
