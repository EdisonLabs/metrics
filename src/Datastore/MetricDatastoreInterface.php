<?php

namespace EdisonLabs\Metrics\Datastore;

/**
 * Interface MetricDatastoreInterface
 */
interface MetricDatastoreInterface
{
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
     * Saves the metrics to the datastore.
     *
     * @return bool
     *   Returns TRUE in case of success, FALSE otherwise.
     */
    public function save();
}
