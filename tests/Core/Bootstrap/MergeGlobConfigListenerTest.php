<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Phalcon\Config;
use Core\Bootstrap\MergeGlobConfigListener as CoreMergeGlobConfigListener;

class MergeGlobConfigListener extends UnitTestCase
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
        $eventsManager->attach('bootstrap', new CoreMergeGlobConfigListener());

        $this->configContainer = $this->app->getDI()->get('config');
        $this->configContainer->configGlobPaths = sys_get_temp_dir() . '/{global,local}.*';

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testGlobalConfigMerge()
    {

        $global = tempnam(sys_get_temp_dir(), 'global.');
        file_put_contents(
            $global,
            '<?php
                return ["global_key" => "global_value"];
            '
        );

        $local = tempnam(sys_get_temp_dir(), 'local.');
        file_put_contents(
            $local,
            '<?php
                return new \Phalcon\Config(["local_key" => "local_value"]);
            '
        );

        $this->app->handle();

        $this->assertEquals('global_value', $this->configContainer->global_key);
        $this->assertEquals('local_value', $this->configContainer->local_key);
    }
}

