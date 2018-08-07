<?php

namespace EdisonLabs\Metrics\Datastore;

/**
 * Interface MetricDatastoreInterface
 */
interface MetricDatastoreInterface
{
    /**
     * MetricDatastoreInterface constructor.
     *
     * @param string $date
     *   The date of the metrics (timestamp).
     * @param array $config
     *   An array containing the config.
     */
    public function __construct($date, array $config = array());

    /**
     * Returns the name of the metric datastore.
     *
     * @return string
     *   The metric datastore name.
     */
    public function getName();

    /**
     * Returns the description of the metric datastore.
     *
     * @return string
     *   The metric datastore description.
     */
    public function getDescription();

    /**
     * Sets the datastore config.
     *
     * @param array $config
     *   An array containing the datastore config.
     */
    public function setConfig(array $config);

    /**
     * Returns the datastore config.
     *
     * @return array
     *   An array containing the datastore config.
     */
    public function getConfig();

    /**
     * Sets the date of the metrics.
     *
     * @param string $date
     *   The date of the metrics.
     */
    public function setDate($date);

    /**
     * Returns the date of the metrics.
     *
     * @return string
     *   Returns a timestamp.
     */
    public function getDate();

    /**
     * Saves the metrics to the datastore.
     *
     * @return bool
     *   Returns TRUE in case of success, FALSE otherwise.
     */
    public function save();
}
