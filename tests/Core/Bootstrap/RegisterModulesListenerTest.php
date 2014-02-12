<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Core\Bootstrap\RegisterModulesListener as CoreRegisterModulesListener;

class RegisterModulesListener extends UnitTestCase
{
    /**
     * @var \Phalcon\Config
     */
    protected $configContainer;

    protected function setUp()
    {
        $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreRegisterModulesListener());

        $this->configContainer = $this->app->getDI()->get('config');

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testMvcModulesRegistration()
    {
        $expectedModuleName = 'TestModule';

        $this->configContainer->modules = [$expectedModuleName];
        $this->app->handle();

        $actualModules = $this->app->getModules();

        $this->assertArrayHasKey($expectedModuleName, $actualModules);
        $this->assertEquals($expectedModuleName . '\Module', $actualModules[$expectedModuleName]['className']);
    }
}

