<?php

namespace EdisonLabs\Metrics\Command;

use EdisonLabs\Metrics\Collector;
use EdisonLabs\Metrics\DatastoreHandler;
use RuntimeException;
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
    protected $config = array();

    /**
     * @var array
     */
    protected $metrics = array();

    /**
     * @var \EdisonLabs\Metrics\DatastoreHandler
     */
    protected $datastoreHandler;

    /**
     * @var \Symfony\Component\Console\Style\SymfonyStyle
     */
    protected $io;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);

        // Gets config.
        $config = $input->getOption('config');
        $config = $this->getConfigArray($config);

        $this->config = $config;

        // Gets the date.
        $date = $input->getOption('date');
        if (!$date = strtotime($date)) {
            throw new RuntimeException('Invalid date string');
        }

        // Sets datastore handler.
        $this->datastoreHandler = new DatastoreHandler($date, $this->config);

        if ($input->getOption('list-datastores')) {
            return;
        }

        // Extracts groups.
        $groups = $input->getOption('groups');
        if ($groups) {
            $groups =  explode(',', $groups);
        }

        // Gets metrics.
        $collector = new Collector($date, $this->config, $groups);
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
            ->addOption('list-datastores', null, InputOption::VALUE_NONE, 'List the available datastores to save the metrics')
            ->addOption('save', null, InputOption::VALUE_REQUIRED, 'Save the metrics to target datastores')
            ->addOption('no-messages', null, InputOption::VALUE_NONE, 'Do not output messages')
            ->addOption('groups', null, InputOption::VALUE_REQUIRED, 'Collect metrics from specific groups only', array())
            ->addOption('config', null, InputOption::VALUE_REQUIRED, 'Pass custom config to the metrics, which can be a file or a string containing JSON format')
            ->addOption('date', null, InputOption::VALUE_REQUIRED, 'Collect metrics from a specific date. Pass a string supported by strtotime()', 'now')
        ;
    }

    /**
     * Converts and returns the config parameter value to array.
     *
     * @param string $config
     *   The config string.
     *
     * @return array
     *   The config array.
     */
    protected function getConfigArray($config)
    {
        if (empty($config)) {
            return array();
        }

        // If parameter config is a file.
        if (file_exists($config)) {
            $config = file_get_contents($config);
        }

        $config = trim($config);

        $config = json_decode($config, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $config;
        }

        throw new RuntimeException('Config parameter must be a valid JSON format');
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
            $metricsOutput['metrics'][get_class($metric)] = array(
                'name' => $metric->getName(),
                'description' => $metric->getDescription(),
                'groups' => $metric->getGroups(),
                'value' => $metric->getMetric(),
            );
        }

        $metricsOutput['timestamp'] = time();

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
     * Outputs the available datastores on table format.
     *
     * @param OutputInterface $output
     *   Console output object.
     */
    protected function outputDatastoresTable(OutputInterface $output)
    {
        $header = array('Name', 'Description');

        $rows = array();
        foreach ($this->datastoreHandler->getDatastores() as $datastore) {
            /** @var \EdisonLabs\Metrics\Datastore\MetricDatastoreInterface $datastore */
            $rows[] = array(
                $datastore->getName(),
                $datastore->getDescription(),
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
        $header = array('Name', 'Description', 'Groups', 'Value');

        $rows = array();
        foreach ($this->metrics as $metric) {
            $groups = implode(', ', $metric->getGroups());

            /** @var \EdisonLabs\Metrics\Metric\MetricInterface $metric */
            $rows[] = array(
                $metric->getName(),
                $metric->getDescription(),
                $groups,
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

        // List datastores.
        if ($input->getOption('list-datastores')) {
            $this->outputDatastoresTable($output);

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
            $datastoresToSave = explode(',', $saveOption);

            foreach ($datastoresToSave as $datastoreName) {
                /** @var \EdisonLabs\Metrics\Datastore\AbstractMetricDatastore $datastore */
                $datastore = $this->datastoreHandler->getDatastoreByName($datastoreName);

                if (!$datastore) {
                    $this->io->warning("Unable to find datastore $datastoreName");
                    continue;
                }

                $datastore->setMetrics($this->metrics);
                if ($datastore->save()) {
                    if (!$noMessages) {
                        $this->io->success("Metrics have been saved to $datastoreName");
                    }
                    continue;
                }

                if (!$noMessages) {
                    $this->io->warning("Unable to save metrics to datastore $datastoreName");
                }
            }
        }
    }
}
