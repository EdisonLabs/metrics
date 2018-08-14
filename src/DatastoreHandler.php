<?php

namespace EdisonLabs\Metrics;

use EdisonLabs\Metrics\Datastore\MetricDatastoreInterface;

/**
 * Class DatastoreHandler
 */
class DatastoreHandler
{

    const DATASTORE_NAMESPACE = 'EdisonLabs\Metric\Datastore';

    /**
     * @var string
     */
    protected $date;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $datastores = array();

    /**
     * DatastoreHandler constructor.
     *
     * @param string $date   The date (timestamp) of the metrics.
     * @param array  $config The custom config array.
     *
     * @throws \Exception
     */
    public function __construct($date, array $config = array())
    {
        $this->config = $config;
        $this->date = $date;
        $this->setDatastores();
    }

    /**
     * Sets a datastore.
     *
     * @param MetricDatastoreInterface $datastore The Metric Datastore object.
     */
    public function setDatastore(MetricDatastoreInterface $datastore)
    {
        $this->datastores[] = $datastore;
    }

    /**
     * Sets datastores.
     *
     * @throws \Exception
     */
    public function setDatastores()
    {
        $containerBuilder = new ContainerBuilder($this->date, $this->config);
        $containerBuilder = $containerBuilder->getContainerBuilder();

        $services = $containerBuilder->getServiceIds();

        foreach ($services as $serviceName) {
            if (strpos($serviceName, self::DATASTORE_NAMESPACE) === false) {
                continue;
            }

            $datastore = $containerBuilder->get($serviceName);

            // Sanity check by class type.
            if (!$datastore instanceof MetricDatastoreInterface) {
                continue;
            }

            $this->setDatastore($datastore);
        }
    }

    /**
     * Returns the available datastores.
     *
     * @return array An array containing the datastores objects.
     */
    public function getDatastores()
    {
        return $this->datastores;
    }

    /**
     * Returns a datastore by a given name.
     *
     * @param string $datastoreName The datastore name.
     *
     * @return object The datastore object.
     */
    public function getDatastoreByName($datastoreName)
    {
        foreach ($this->getDatastores() as $datastore) {
            if ($datastore->getName() == $datastoreName) {
                return $datastore;
            }
        }
    }
}
