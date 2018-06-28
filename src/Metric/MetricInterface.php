<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Interface MetricInterface.
 *
 * @package EdisonLabs\Metrics\Metric
 */
interface MetricInterface
{
    /**
     * Returns the name of the metric.
     *
     * @return string
     *   The metric name.
     */
    public function getName();

    /**
     * Returns the description of the metric.
     *
     * @return string
     *   The metric description.
     */
    public function getDescription();

    /**
     * Returns the metric value.
     *
     * @return mixed
     *   The metric value.
     */
    public function getMetric();

}
