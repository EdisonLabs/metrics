<?php

namespace EdisonLabs\Metrics;

use EdisonLabs\Metrics\Metric\Storage\MetricStorageInterface;

/**
 * Class StorageHandler
 */
class StorageHandler
{

    const STORAGES_NAMESPACE = 'EdisonLabs\Metrics\Storages';

    /**
     * @var array
     */
    protected $storages = array();

    /**
     * StorageHandler constructor.
     */
    public function __construct()
    {
        $this->setStorages();
    }

    /**
     * Sets a Storage.
     *
     * @param MetricStorageInterface $storage
     *   The Metric Storage object.
     */
    public function setStorage(MetricStorageInterface $storage)
    {
        $this->storages[] = $storage;
    }

    /**
     * Sets storages.
     *
     * @throws \Exception
     */
    public function setStorages()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder = $containerBuilder->getContainerBuilder();

        $services = $containerBuilder->getServiceIds();

        foreach ($services as $serviceName) {
            if (strpos($serviceName, self::STORAGES_NAMESPACE) === false) {
                continue;
            }

            $storage = $containerBuilder->get($serviceName);

            // Sanity check by class type.
            if (!$storage instanceof MetricStorageInterface) {
                continue;
            }

            $this->setStorage($storage);
        }
    }

    /**
     * Returns the available storages.
     *
     * @return array
     *   An array containing the storages objects.
     */
    public function getStorages()
    {
        return $this->storages;
    }

    /**
     * Returns a storage by a given name.
     *
     * @param string $storageName
     *   The storage name.
     *
     * @return object
     *   The storage object.
     */
    public function getStorageByName($storageName)
    {
        foreach ($this->getStorages() as $storage) {
            if ($storage->getName() == $storageName) {
                return $storage;
            }
        }
    }
}
