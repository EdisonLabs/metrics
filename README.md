[![Build Status](https://travis-ci.com/EdisonLabs/metrics.svg?branch=1.x)](https://travis-ci.com/EdisonLabs/metrics) [![Coverage Status](https://coveralls.io/repos/github/EdisonLabs/metrics/badge.svg?branch=1.x)](https://coveralls.io/github/EdisonLabs/metrics?branch=1.x)

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
// src/EdisonLabs/Metric/NumberOfFiles.php

namespace EdisonLabs\Metric;

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
// src/EdisonLabs/Metric/NumberOfPhpFiles.php

namespace EdisonLabs\Metric;

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
// src/EdisonLabs/Metric/PercentageOfPhpFiles.php

namespace EdisonLabs\Metric;

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

Configure the autoload on the `composer.json` file:
```json
"autoload": {
    "psr-4": {
        "EdisonLabs\\Metric\\": "src/EdisonLabs/Metric"
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
Create datastore classes to save your metrics:

```php
// src/EdisonLabs/Metric/Datastore/SqLite.php

namespace EdisonLabs\Metric\Datastore;

use EdisonLabs\Metrics\Metric\Datastore\AbstractMetricDatastore;

class SqLite extends AbstractMetricDatastore
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
// datastore.php

use EdisonLabs\Metrics\Collector;
use EdisonLabs\Metrics\DatastoreHandler;

$collector = new Collector();
$metrics = $collector->getMetrics();

$datastoreHandler = new DatastoreHandler();
$datastore = $datastoreHandler->getDatastoreByName('SQLite');
$datastore->setMetrics($metrics);
$datastore->save();
```

#### Command
Use the option `--save` to list and save your metrics.
```
metrics --save=SQLite
```

The `--save` option accepts multiple values.
```
metrics --save=SQLite,MySql,MyCustomDatastore
```
