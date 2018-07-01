<?php

namespace EdisonLabs\Metrics\Metric\Storage;

/**
 * Interface MetricStorageInterface
 * @package EdisonLabs\Metrics\Metric\Storage
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

}