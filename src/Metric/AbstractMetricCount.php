<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class AbstractMetricCount.
 */
abstract class AbstractMetricCount implements MetricCountInterface
{

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * {@inheritdoc}
     */
    abstract public function getDescription();

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        // No groups by default.
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getMetric()
    {
        static $count;

        if (!isset($count)) {
            $count = $this->calculate();
        }

        return $count;
    }
}
