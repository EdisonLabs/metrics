<?php

namespace EdisonLabs\Metrics\Datastore;

/**
 * Class AbstractMetricDatastore
 */
abstract class AbstractMetricDatastore implements MetricDatastoreInterface
{

    /**
     * @var string
     */
    protected $date;

    /**
     * The metrics config.
     *
     * @var array
     */
    protected $config = array();

    /**
     * @var array
     */
    protected $metrics = array();

    /**
     * {@inheritdoc}
     */
    public function __construct($date, array $config = array())
    {
        $this->setDate($date);
        $this->setConfig($config);
    }

    /**
     * {@inheritdoc}
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Set the metrics to be saved.
     *
     * @param array $metrics
     *   An array containing the metrics objects.
     */
    public function setMetrics(array $metrics)
    {
        $this->metrics = $metrics;
    }

    /**
     * {@inheritdoc}
     */
    public function setDate($date)
    {
        return $this->date = $date;
    }

    /**
     * {@inheritdoc}
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Returns the metrics that will be saved.
     *
     * @return array
     *   An array containing the metrics objects.
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
}
