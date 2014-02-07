<?php

namespace Mahout;

class TestTest extends \PHPUnit_Framework_TestCase
{

    public function testMethod()
    {
        $test = new \Mahout\Test();
        $name = $test->method("foo");

        $this->assertEquals("Foo", $name);
    }
}
