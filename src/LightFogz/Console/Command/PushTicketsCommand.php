<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ollie Maitland
 * Date: 09/02/13
 * Time: 14:38
 * To change this template use File | Settings | File Templates.
 */

namespace LightFogz\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushTicketsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('push:tickets');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //
        $app = $this->getApplication()->getSilexApplication();
        $client = new \LightFogz\Lighthouse\Client($app['config']);

        // get projects
        $tickets = $client->getTickets();

        $i = 0;
        foreach ($tickets as $ticket) {

            // create the new message
            $msg = new \LightFogz\Messages\FogbugzMessage();
            $msg->setSubject($ticket->title);
            $msg->setFrom($ticket->author);
            $msg->setTo("support@byng-systems.com");
            $msg->setBody($ticket->body);
//            $app['mailer']->send($msg);
            $i++;
        }

        $output->writeln(sprintf('<info>Created %s cases</info>', $i));
    }
}