<?php

namespace EdisonLabs\Metrics\Metric\Storage;

/**
 * Class MetricStorage
 */
abstract class AbstractMetricStorage implements MetricStorageInterface
{

    /**
     * @var array
     */
    protected $metrics = array();

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * {@inheritdoc}
     */
    abstract public function getDescription();

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
     * Returns the metrics that will be saved.
     *
     * @return array
     *   An array containing the metrics objects.
     */
    public function getMetrics()
    {
        return $this->metrics;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function save();
}
