<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Core\Bootstrap\RegisterModulesPathsListener as CoreRegisterModulesPathsListener;

class RegisterModulesPathsListener extends UnitTestCase
{
    protected function setUp()
    {
        $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreRegisterModulesPathsListener());

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testRegisteringDirsForModules()
    {
        $configContainer = $this->app->getDI()->get('config');
        $configContainer->modulePaths = [__DIR__];

        $this->app->handle();

        $dirs = $this->app->getDI()->get('loader')->getDirs();
        $this->assertContains(__DIR__, $dirs);
    }
}

