[![Build Status](https://travis-ci.com/EdisonLabs/metrics.svg?branch=1.x)](https://travis-ci.com/EdisonLabs/metrics)

# Metrics Collector

## Overview
Simple library that provides base classes for you to easily extend and collect your custom metrics.

## Usage

This library does not provide any metrics by default, to create your own metrics, first [create a Composer package](https://getcomposer.org/doc/01-basic-usage.md), then add the dependency of this package

```
composer require edisonlabs/metrics
```

Now, create your metrics classes extending `edisonlabs/metrics` classes. 

On the following example, we will create three metrics:
- Total number of files that a location has.
- Total number of PHP files.
- Percentage of PHP files

Create total of files metric.
```php
// src/EdisonLabs/Metrics/Counts/TotalOfFiles.php

namespace EdisonLabs\Metrics\Counts;

use EdisonLabs\Metrics\Metric\AbstractMetricCount;

class TotalOfFiles extends AbstractMetricCount
{
    public function getName()
    {
        return 'Total of files';
    }

    public function getDescription()
    {
        return 'The total number of files';
    }

    public function calculate()
    {
        // Put the logic to calculate the total here.
        // ..
        
        // Random example.
        return rand(10, 100);
    }
}
```

Create total of PHP files metric.
```php
// src/EdisonLabs/Metrics/Counts/TotalOfPhpFiles.php

namespace EdisonLabs\Metrics\Counts;

use EdisonLabs\Metrics\Metric\AbstractMetricCount;

class TotalOfPhpFiles extends AbstractMetricCount
{
    public function getName()
    {
        return 'Total of PHP files';
    }

    public function getDescription()
    {
        return 'The total number of PHP files';
    }

    public function calculate()
    {
        // Put the logic to calculate the total of PHP files here.
        // ..
        
        // Random example.
        return rand(10, 50);
    }
}
```

Create the percentage of PHP files metric.
```php
// src/EdisonLabs/Metrics/Percentages/PercentageOfPhpFiles.php

namespace EdisonLabs\Metrics\Percentages;

use EdisonLabs\Metrics\Counts\TotalOfFiles;
use EdisonLabs\Metrics\Counts\TotalOfPhpFiles;
use EdisonLabs\Metrics\Metric\AbstractMetricPercentage;

class PercentageOfPhpFiles extends AbstractMetricPercentage
{
    public function __construct(TotalOfFiles $total, TotalOfPhpFiles $count)
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

Important: You must use the namespaces `EdisonLabs\Metrics\Percentages` or `EdisonLabs\Metrics\Counts` when creating your classes.

Next step, configure the autoload on your `composer.json` file
```json
"autoload": {
    "psr-4": {
        "EdisonLabs\\Metrics\\": "src/EdisonLabs/Metrics"
    }
}
```

Recreate the Composer autoloader.
```
composer dump-autoload
```

## Collecting metrics

There are two ways to collect your metrics, programmatically and by command line.

 #### Programmatically
 ```php
 // collector.php
 
use EdisonLabs\Metrics\Collector;

$collector = new Collector();
$metrics = $collector->getMetrics();
```
 
#### Command

The command is located at `vendor/bin/metrics`. You can include the `vendor/bin` directory to your system `$PATH` to run this command from anywhere.

Type `metrics --help` to see all the available options.

## Saving metrics
You can also create storages classes to save your metrics.

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

Use the command option `--save` to save your metrics.
```
metrics --save=SQLite
```

The `--save` option accepts multiple values.
```
metrics --save=SQLite,MySql,MyCustomStorage
```
