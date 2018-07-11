<?php

namespace EdisonLabs\Metrics\Storage;

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
}
