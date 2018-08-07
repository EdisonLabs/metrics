<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Interface MetricInterface.
 */
interface MetricInterface
{
    /**
     * MetricInterface constructor.
     *
     * @param string $date
     *   The metric date (timestamp).
     * @param array $config
     *   An array containing the metric config.
     */
    public function __construct($date, array $config = array());

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
     * Sets the metrics config.
     *
     * @param array $config
     *   An array containing the metrics config.
     */
    public function setConfig(array $config);

    /**
     * Returns the metrics config.
     *
     * @return array
     *   An array containing the metrics config.
     */
    public function getConfig();

    /**
     * Return the groups that this metric belongs to.
     *
     * @return array
     *   A list of groups.
     */
    public function getGroups();

    /**
     * Sets the date of the metric.
     *
     * @param string $date
     *   The date of the metric.
     */
    public function setDate($date);

    /**
     * Returns the date of the metric.
     *
     * @return string
     *   Returns a timestamp.
     */
    public function getDate();

    /**
     * Returns the metric value.
     *
     * @return mixed
     *   The metric value.
     */
    public function getMetric();
}
