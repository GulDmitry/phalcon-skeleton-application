<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Core\Bootstrap\LoadModulesListener as CoreLoadModulesListener;

class LoadModulesListener extends UnitTestCase
{
    protected function setUp()
    {
        $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreLoadModulesListener());

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testModulesAutoloadingModules()
    {
        $applicationModuleMock = $this->getMock('Application\Module', array('registerAutoloaders'));
        $applicationModuleMock->expects($this->once())->method('registerAutoloaders');

        $adminModuleMock = $this->getMock('Admin\Module', array('registerAutoloaders'));
        $adminModuleMock->expects($this->once())->method('registerAutoloaders');

        $modulesConfig = [
            'Application' => [
                'className' => 'Application\Module',
                'object' => $applicationModuleMock,
            ],
            'Admin' => [
                'className' => 'Admin\Module',
                'object' => $adminModuleMock,
            ],
        ];
        $this->app->registerModules($modulesConfig, true);
        $this->app->handle();
    }
}

