<?php

namespace EdisonLabs\Metrics\Command;

use EdisonLabs\Metrics\Collector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MetricsCommand
 * @package EdisonLabs\Metrics\Command
 */
class MetricsCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('edisonlabs:metrics')
            ->setDescription('Edison Labs metrics collector')
            ->setHelp('This command allows you to list and save the collected metrics.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collector = new Collector();
        $metrics = $collector->getMetrics();

        echo "<pre>" . print_r($metrics, TRUE) . "</pre>"; die;
    }

}
