<?php

namespace ZablockiBros\Jetpack\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * If true, setup has run at least once.
     *
     * @var bool
     */
    protected static $setUpRun = false;

    /**
     * Setup the test environment.
     *
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        Carbon::setTestNow(now());

        if (! static::$setUpRun) {
            \Artisan::call('migrate:fresh');
            //\Artisan::call('db:seed', ['--class' => 'TestDatabaseSeeder']);
            static::$setUpRun = true;
        }
    }
}
