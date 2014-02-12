<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Phalcon\Config;
use Core\Bootstrap\MergeModulesConfigListener as CoreMergeModulesConfigListener;

class MergeModulesConfigListener extends UnitTestCase
{
    protected function setUp()
    {
        $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreMergeModulesConfigListener());

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testMergeModuleConfigs()
    {
        $applicationModuleMock = $this->getMock('Application\Module', array('getConfig'));
        $applicationModuleMock->expects($this->once())
            ->method('getConfig')->will($this->returnValue(new Config(['test_key_1' => 1])));

        $adminModuleMock = $this->getMock('Admin\Module', array('getConfig'));
        $adminModuleMock->expects($this->once())
            ->method('getConfig')->will($this->returnValue(['test_key_2' => 2]));

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

        $actualConfig = $this->app->getDI()->get('config');

        $this->assertEquals(1, $actualConfig->test_key_1);
        $this->assertEquals(2, $actualConfig->test_key_2);
    }
}

