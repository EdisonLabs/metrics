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
 * @package EdisonLabs\Metrics\Command
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
            ->addOption('save', null, InputOption::VALUE_REQUIRED, 'Save the metrics to target storages');
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
        $metrics_output = array();
        foreach ($this->metrics as $metric) {
            /** @var \EdisonLabs\Metrics\Metric\MetricInterface $metric */
            $metrics_output[get_class($metric)] = array(
                'name' => $metric->getName(),
                'description' => $metric->getDescription(),
                'value' => $metric->getMetric(),
            );
        }

        $metrics_json = json_encode($metrics_output);
        $output->writeln($metrics_json);
    }

    /**
     * Outputs a table.
     *
     * @param array $header
     *   An array containing the header columns.
     * @param array $rows
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
    protected function outputStoragesTable(OutputInterface $output) {
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
    protected function outputMetricsTable(OutputInterface $output) {
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
        // List storages.
        if ($input->getOption('list-storages')) {
            $this->outputStoragesTable($output);
            return;
        }

        // List metrics.
        $format = $input->getOption('format');
        if ($format == 'json') {
            $this->outputMetricsJson($output);
        }
        else {
            $this->outputMetricsTable($output);
        }

        // Save metrics.
        $save_option = $input->getOption('save');
        if ($save_option) {
            $storages_to_save = explode(',', $save_option);

            foreach ($storages_to_save as $storage_name) {
                /** @var \EdisonLabs\Metrics\Metric\Storage\MetricStorage $storage */
                $storage = $this->storageHandler->getStorageByName($storage_name);

                if (!$storage) {
                    $this->io->warning("Unable to find storage $storage_name");
                    continue;
                }

                $storage->setMetrics($this->metrics);
                $storage->save();

                $this->io->success("Metrics have been saved to $storage_name");
            }
        }
    }

}
