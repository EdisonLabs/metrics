<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class MetricCount.
 *
 * @package EdisonLabs\Metrics\Metric
 */
abstract class MetricCount implements MetricInterface
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
     * Calculates the count.
     *
     * @return int
     *   The count value.
     */
    abstract public function calculate();

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
