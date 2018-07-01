<?php

namespace EdisonLabs\Metrics;

use EdisonLabs\Metrics\Metric\Storage\MetricStorageInterface;

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
        $container_builder = new ContainerBuilder();
        $container_builder = $container_builder->getContainerBuilder();

        $services = $container_builder->getServiceIds();

        foreach ($services as $service_name) {
            if (strpos($service_name, self::STORAGES_NAMESPACE) === false) {
                continue;
            }

            $storage = $container_builder->get($service_name);

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
     * @param string $storage_name
     *   The storage name.
     *
     * @return object
     *   The storage object.
     */
    public function getStorageByName($storage_name) {
        foreach ($this->getStorages() as $storage) {
            if ($storage->getName() == $storage_name) {
                return $storage;
            }
        }
    }

}
