<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Class AbstractMetricCount.
 */
abstract class AbstractMetricCount extends AbstractMetricBase
{

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
