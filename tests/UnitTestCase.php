<?php
namespace Test;

use \Phalcon\DI,
    \Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase
{
    /**
     * @var bool
     */
    private $loaded = false;

    protected function setUp()
    {
        // Load any additional services that might be required during testing.
        $di = DI::getDefault();

        // Get any DI components here. If you have a config, be sure to pass it to the parent.

        parent::setUp($di);

        $this->loaded = true;
    }

    /**
     * Check if the test case is setup properly
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }
    }
}
