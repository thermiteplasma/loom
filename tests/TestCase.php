<?php

namespace Thermiteplasma\Loom\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Thermiteplasma\Loom\LoomServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LoomServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
