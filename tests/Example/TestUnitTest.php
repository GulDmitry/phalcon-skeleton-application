<?php
namespace Test\Example;

use Test\UnitTestCase;

/**
 * Class UnitTest
 */
class UnitTest extends UnitTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testTestCase()
    {
        $this->assertEquals(
            'works',
            'works',
            'This is OK'
        );

        $this->assertEquals(
            'works',
            'works1',
            'This wil fail'
        );

    }
}