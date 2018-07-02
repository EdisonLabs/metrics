<?php

namespace EdisonLabs\Metrics\Command;

use EdisonLabs\Metrics\Collector;
use EdisonLabs\Metrics\StorageHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MetricsCommand
 */
class MetricsCommand extends Command
{

    /**
     * @var array
     */
    protected $metrics = array();

    /**
     * @var \EdisonLabs\Metrics\StorageHandler
     */
    protected $storageHandler;

    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $io;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->storageHandler = new StorageHandler();
        $this->io = new SymfonyStyle($input, $output);

        if ($input->getOption('list-storages')) {
            return;
        }

        $collector = new Collector();

        $this->metrics = $collector->getMetrics();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('edisonlabs:metrics')
            ->setDescription('Edison Labs metrics collector')
            ->setHelp('This command allows you to list and save the collected metrics.')
            ->addOption('format', null, InputOption::VALUE_REQUIRED, 'The output format: table, json', 'table')
            ->addOption('list-storages', null, InputOption::VALUE_NONE, 'List the available storages to save the metrics')
            ->addOption('save', null, InputOption::VALUE_REQUIRED, 'Save the metrics to target storages')
            ->addOption('no-messages', null, InputOption::VALUE_NONE, 'Do not output messages');
        ;
    }

    /**
     * Outputs the metrics on Json format.
     *
     * @param OutputInterface $output
     *   Console output object.
     */
    protected function outputMetricsJson(OutputInterface $output)
    {
        $metricsOutput = array();
        foreach ($this->metrics as $metric) {
            /** @var \EdisonLabs\Metrics\Metric\MetricInterface $metric */
            $metricsOutput[get_class($metric)] = array(
                'name' => $metric->getName(),
                'description' => $metric->getDescription(),
                'value' => $metric->getMetric(),
            );
        }

        $metricsJson = json_encode($metricsOutput);
        $output->writeln($metricsJson);
    }

    /**
     * Outputs a table.
     *
     * @param array           $header
     *   An array containing the header columns.
     * @param array           $rows
     *   An array containing the table rows.
     * @param OutputInterface $output
     *   Console output object.
     */
    protected function outputTable(array $header, array $rows, OutputInterface $output)
    {
        $table = new Table($output);

        $table
            ->setHeaders($header)
            ->setRows($rows)
        ;
        $table->render();
    }

    /**
     * Outputs the available storages on table format.
     *
     * @param OutputInterface $output
     *   Console output object.
     */
    protected function outputStoragesTable(OutputInterface $output)
    {
        $header = array('Name', 'Description');

        $rows = array();
        foreach ($this->storageHandler->getStorages() as $storage) {
            /** @var \EdisonLabs\Metrics\Metric\Storage\MetricStorageInterface $storage */
            $rows[] = array(
                $storage->getName(),
                $storage->getDescription(),
            );
        }

        $this->outputTable($header, $rows, $output);
    }

    /**
     * Outputs the metrics on table format.
     *
     * @param OutputInterface $output
     *   Console output object.
     */
    protected function outputMetricsTable(OutputInterface $output)
    {
        $header = array('Name', 'Description', 'Value');

        $rows = array();
        foreach ($this->metrics as $metric) {
            /** @var \EdisonLabs\Metrics\Metric\MetricInterface $metric */
            $rows[] = array(
                $metric->getName(),
                $metric->getDescription(),
                $metric->getMetric(),
            );
        }

        $this->outputTable($header, $rows, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $noMessages = $input->getOption('no-messages');

        // List storages.
        if ($input->getOption('list-storages')) {
            $this->outputStoragesTable($output);

            return;
        }

        // List metrics.
        $format = $input->getOption('format');
        if ('json' == $format) {
            $this->outputMetricsJson($output);
        } else {
            $this->outputMetricsTable($output);
        }

        // Save metrics.
        $saveOption = $input->getOption('save');
        if ($saveOption) {
            $storagesToSave = explode(',', $saveOption);

            foreach ($storagesToSave as $storageName) {
                /** @var \EdisonLabs\Metrics\Metric\Storage\AbstractMetricStorage $storage */
                $storage = $this->storageHandler->getStorageByName($storageName);

                if (!$storage) {
                    $this->io->warning("Unable to find storage $storageName");
                    continue;
                }

                $storage->setMetrics($this->metrics);
                if ($storage->save()) {
                    if (!$noMessages) {
                        $this->io->success("Metrics have been saved to $storageName");
                    }
                    continue;
                }

                if (!$noMessages) {
                    $this->io->warning("Unable to save metrics to storage $storageName");
                }
            }
        }
    }
}
