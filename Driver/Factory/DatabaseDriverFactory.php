<?php

namespace Driver\Factory;

use Driver\ElasticSearchDriver;
use Driver\MySQLDriver;

class DatabaseDriverFactory
{
    public static function create()
    {
        /*TODO vložit název DB driveru z konfigurace*/
        $driverName = '';
        switch ($driverName) {
            case 'ElasticSearch':
                return new ElasticSearchDriver();
            case 'MySQLDriver':
                return new MySQLDriver();
            default:
                throw new \Exception('Uknown database driver');
        }
    }
}