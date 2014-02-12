<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Core\Bootstrap\BootstrapModulesListener as CoreBootstrapModules;

class BootstrapModulesListener extends UnitTestCase
{
    protected function setUp()
    {
        $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreBootstrapModules());

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testBootstrapModules()
    {
        $applicationModuleMock = $this->getMock('Application\Module', array('onBootstrap'));
        $applicationModuleMock->expects($this->once())->method('onBootstrap');

        $adminModuleMock = $this->getMock('Admin\Module', array('onBootstrap'));
        $adminModuleMock->expects($this->once())->method('onBootstrap');

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

