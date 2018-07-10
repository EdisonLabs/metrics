<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Interface MetricInterface.
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
     * Return the groups that this metric belongs to.
     *
     * @return array
     *   A list of groups.
     */
    public function getGroups();

    /**
     * Returns the metric value.
     *
     * @return mixed
     *   The metric value.
     */
    public function getMetric();
}
