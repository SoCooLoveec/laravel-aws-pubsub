<?php

namespace PodPoint\AwsPubSub\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as Orchestra;
use PodPoint\AwsPubSub\PubSubServiceProvider;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase,
        WithFaker;

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PubSubServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        /** PUB */
        $app['config']->set('broadcasting.default', 'sns');
        $app['config']->set('broadcasting.connections.sns', [
            'driver' => 'sns',
            'key' => 'dummy-key',
            'secret' => 'dummy-secret',
            'arn-prefix' => 'aws:arn:12345:',
            'arn-suffix' => '',
            'region' => 'eu-west-1',
        ]);

        /** SUB */
        $app['config']->set('queue.connections.pub-sub', [
            'driver' => 'sqs-sns',
            'key' => 'dummy-key',
            'secret' => 'dummy-secret',
            'prefix' => 'https://sqs.eu-west-1.amazonaws.com/13245',
            'queue' => 'default',
            'suffix' => '',
            'region' => 'eu-west-1',
        ]);

        /** DATABASE */
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
    }
}
