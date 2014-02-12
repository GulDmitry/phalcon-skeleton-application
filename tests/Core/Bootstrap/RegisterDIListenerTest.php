<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Phalcon\Config;
use Core\Bootstrap\RegisterDIListener as CoreRegisterDIListener;

class RegisterDIListener extends UnitTestCase
{
    protected function setUp()
    {
        $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreRegisterDIListener());

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testDIPartsFromConfig()
    {
        $configContainer = $this->app->getDI()->get('config');
        $configContainer->di = [
            'custom_container' => [
                new Config(),
                true,
            ]
        ];
        $this->app->handle();

        $actualContainder = $this->app->getDI()->get('custom_container');

        $this->assertInstanceOf('\Phalcon\Config', $actualContainder);
    }
}

