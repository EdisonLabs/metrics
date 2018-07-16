<?php

namespace EdisonLabs\Metrics\Storage;

/**
 * Interface MetricStorageInterface
 */
interface MetricStorageInterface
{
    /**
     * Returns the name of the metric storage.
     *
     * @return string
     *   The metric storage name.
     */
    public function getName();

    /**
     * Returns the description of the metric storage.
     *
     * @return string
     *   The metric storage description.
     */
    public function getDescription();

    /**
     * Sets the storage config.
     *
     * @param array $config
     *   An array containing the storage config.
     */
    public function setConfig(array $config);

    /**
     * Returns the storage config.
     *
     * @return array
     *   An array containing the storage config.
     */
    public function getConfig();

    /**
     * Saves the metrics to the storage.
     *
     * @return bool
     *   Returns TRUE in case of success, FALSE otherwise.
     */
    public function save();
}
