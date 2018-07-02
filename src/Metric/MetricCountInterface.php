<?php

namespace EdisonLabs\Metrics\Metric;

/**
 * Interface MetricInterface.
 */
interface MetricCountInterface extends MetricInterface
{

    /**
     * Calculates the count.
     *
     * @return int
     *   The count value.
     */
    public function calculate();
}
