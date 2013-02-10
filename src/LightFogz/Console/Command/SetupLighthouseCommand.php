<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ollie Maitland
 * Date: 09/02/13
 * Time: 14:39
 * To change this template use File | Settings | File Templates.
 */

namespace LightFogz\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetupLighthouseCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('setup:lighthouse')
            ->addArgument('lighthouse-url', InputArgument::REQUIRED, 'Lighthouse API end point URL')
            ->addArgument('lighthouse-token', InputArgument::REQUIRED, 'Lighthouse API token')
            ->addArgument('lighthouse-date', InputArgument::OPTIONAL, 'Lighthouse sync start date')
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // save all the arguments
        $app = $this->getApplication()->getSilexApplication();

        $args = $input->getArguments();
        unset($args['command']);

        $configuration = $app['config'];
        /* @var \LightFogz\Entities\Configuration $configuration */
        $configuration->fromArray($args);
        $configuration->save();

        $output->writeln('<info>Configuration saved</info>');
    }
}