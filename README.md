[![Build Status](https://travis-ci.com/EdisonLabs/metrics.svg?branch=1.x)](https://travis-ci.com/EdisonLabs/metrics)

# Metrics Collector

## Overview
The Metrics Collector is a simple library that provides base classes to easily extend and collect custom metrics.

## Usage

This library does not provide any metrics by default. To create your own metrics, [create a Composer package](https://getcomposer.org/doc/01-basic-usage.md) and then add a dependency to this package:

```
composer require edisonlabs/metrics
```

Now create your metrics classes extending `edisonlabs/metrics` classes.

In the following example, we will create three metrics:
- Total number of files that a location has
- Total number of PHP files
- Percentage of PHP files

Create number of files metric:
```php
// src/EdisonLabs/Metrics/Counts/NumberOfFiles.php

namespace EdisonLabs\Metrics\Counts;

use EdisonLabs\Metrics\Metric\AbstractMetricBase;

class NumberOfFiles extends AbstractMetricBase
{
    public function getName()
    {
        return 'Number of files';
    }

    public function getDescription()
    {
        return 'The total number of files';
    }

    public function getMetric()
    {
        // Put the logic to calculate the total here.
        // ..

        // Random example.
        return rand(10, 100);
    }
}
```

Create number of PHP files metric:
```php
// src/EdisonLabs/Metrics/Counts/NumberOfPhpFiles.php

namespace EdisonLabs\Metrics\Counts;

use EdisonLabs\Metrics\Metric\AbstractMetricBase;

class NumberOfPhpFiles extends AbstractMetricBase
{
    public function getName()
    {
        return 'Number of PHP files';
    }

    public function getDescription()
    {
        return 'The total number of PHP files';
    }

    public function getMetric()
    {
        // Put the logic to calculate the total of PHP files here.
        // ..

        // Random example.
        return rand(10, 50);
    }
}
```

Create the percentage of PHP files metric:
```php
// src/EdisonLabs/Metrics/Percentages/PercentageOfPhpFiles.php

namespace EdisonLabs\Metrics\Percentages;

use EdisonLabs\Metrics\Counts\NumberOfFiles;
use EdisonLabs\Metrics\Counts\NumberOfPhpFiles;
use EdisonLabs\Metrics\Metric\AbstractMetricPercentage;

class PercentageOfPhpFiles extends AbstractMetricPercentage
{
    public function __construct(NumberOfFiles $total, NumberOfPhpFiles $count)
    {
        parent::__construct($total, $count);
    }

    public function getName()
    {
        return 'PHP files (%)';
    }

    public function getDescription()
    {
        return 'Percentage of PHP files';
    }
}
```

*You must use the namespaces `EdisonLabs\Metrics\Percentages` or `EdisonLabs\Metrics\Counts` when creating the classes.*

Configure the autoload on the `composer.json` file:
```json
"autoload": {
    "psr-4": {
        "EdisonLabs\\Metrics\\": "src/EdisonLabs/Metrics"
    }
}
```

Re-create the Composer autoloader:
```
composer dump-autoload
```

## Collecting metrics

There are two ways to collect the metrics: programmatically and by command-line.

#### Programmatically

```php
// collector.php

use EdisonLabs\Metrics\Collector;

$collector = new Collector();
$metrics = $collector->getMetrics();
```

#### Command

The command is located at `vendor/bin/metrics`. Include the `vendor/bin` directory in the system `$PATH` to run this command from anywhere.

Type `metrics --help` to see all the available options.

## Saving metrics
Create storage classes to save your metrics:

```php
// src/EdisonLabs/Metrics/Storages/SqLite.php

namespace EdisonLabs\Metrics\Storages;

use EdisonLabs\Metrics\Metric\Storage\AbstractMetricStorage;

class SqLite extends AbstractMetricStorage
{
    public function getName()
    {
        return 'SQLite';
    }

    public function getDescription()
    {
        return 'Stores metrics to SQLite';
    }

    public function save()
    {
        $metrics = $this->getMetrics();

        // Put your logic to store the metrics to SQLite here.
        return true;
    }
}
```

#### Programmatically
```php
// storage.php

use EdisonLabs\Metrics\Collector;
use EdisonLabs\Metrics\StorageHandler;

$collector = new Collector();
$metrics = $collector->getMetrics();

$storageHandler = new StorageHandler();
$storage = $storageHandler->getStorageByName('SQLite');
$storage->setMetrics($metrics);
$storage->save();
```

#### Command
Use the option `--save` to list and save your metrics.
```
metrics --save=SQLite
```

The `--save` option accepts multiple values.
```
metrics --save=SQLite,MySql,MyCustomStorage
```
