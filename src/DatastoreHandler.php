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
     * @param array $config
     *   The custom config array.
     * @param \EdisonLabs\Metrics\Datastore\MetricDatastoreInterface[] $datastores
     *   (optional) The array of MetricDatastore to set.
     *
     * @throws \Exception
     */
    public function __construct(array $config = [], array $datastores = [])
    {
        $this->config = $config;
        $this->setDatastores($datastores);
    }

    /**
     * Sets a datastore.
     *
     * @param MetricDatastoreInterface $datastore
     *   The Metric Datastore object.
     */
    public function setDatastore(MetricDatastoreInterface $datastore)
    {
        $this->datastores[] = $datastore;
    }

    /**
     * Sets datastores.
     *
     * @param \EdisonLabs\Metrics\Datastore\MetricDatastoreInterface[] $datastores
     *   (optional) The array of MetricDatastore to set.
     *
     * @throws \Exception
     */
    public function setDatastores(array $datastores = [])
    {
        if (!empty($datastores)) {
            foreach ($datastores as $datastore) {
                $this->setDatastore($datastore);
                return;
            }
        }

        $containerBuilder = new ContainerBuilder();
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

            $datastore->setConfig($this->config);

            $this->setDatastore($datastore);
        }
    }

    /**
     * Returns the available datastores.
     *
     * @return array
     *   An array containing the datastores objects.
     */
    public function getDatastores()
    {
        return $this->datastores;
    }

    /**
     * Returns a datastore by a given name.
     *
     * @param string $datastoreName
     *   The datastore name.
     *
     * @return object
     *   The datastore object.
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
