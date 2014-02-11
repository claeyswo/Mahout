<?php
/*
 * This file is part of the Mahout package.
 *
 * (c) Aurimas Niekis <aurimas.niekis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mahout\Tests\Functional\Utils;

use Mahout\Utils;
use Mahout\Utils\KeySpaceConstrainedParams;

/**
 * Class KeySpaceConstrainedParamsTest
 *
 * @package Mahout\Tests\Functional\Utils
 */
class KeySpaceConstrainedParamsTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $obj = new KeySpaceConstrainedParams();

        $this->assertObjectHasAttribute("limit", $obj, "Object doesn't have `limit` attribute");
        $this->assertObjectHasAttribute("size", $obj, "Object doesn't have `size` attribute");
        $this->assertObjectHasAttribute("params", $obj, "Object doesn't have `params` attribute");

        $this->assertAttributeEquals(
            Utils::KEY_SPACE_LIMIT,
            "limit",
            $obj,
            "Attribute `limit` doesn't match default value defined in `Utils::KEY_SPACE_LIMIT`"
        );

        $this->assertAttributeEquals(
            0,
            "size",
            $obj,
            "Attribute `size` doesn't match default value `0`"
        );

        $this->assertAttributeEquals(
            array(),
            "params",
            $obj,
            "Attribute `params` doesn't match default value `array()`"
        );
    }

    public function testConstructorWithParam()
    {
        $obj = new KeySpaceConstrainedParams(100);

        $this->assertObjectHasAttribute("limit", $obj, "Object doesn't have `limit` attribute");
        $this->assertObjectHasAttribute("size", $obj, "Object doesn't have `size` attribute");
        $this->assertObjectHasAttribute("params", $obj, "Object doesn't have `params` attribute");

        $this->assertAttributeEquals(
            100,
            "limit",
            $obj,
            "Attribute `limit` doesn't match default value `100`"
        );

        $this->assertAttributeEquals(
            0,
            "size",
            $obj,
            "Attribute `size` doesn't match default value `0`"
        );

        $this->assertAttributeEquals(
            array(),
            "params",
            $obj,
            "Attribute `params` doesn't match default value `array()`"
        );
    }

    public function testOffsetGet()
    {
        $obj = new KeySpaceConstrainedParams();
        $this->assertNull($obj->offsetGet("foo"), "OffsetGet should return null on undefined key");

        $obj->params["foo"] = "bar";
        $this->assertEquals("bar", $obj->offsetGet("foo"), "OffsetGet should return 'bar' on defined key");
    }

    public function testArrayAccessGet()
    {
        $obj = new KeySpaceConstrainedParams();
        $this->assertNull($obj["foo"], "OffsetGet should return null on undefined key");

        $obj->params["foo"] = "bar";
        $this->assertEquals("bar", $obj["foo"], "OffsetGet should return 'bar' on defined key");
    }

    public function testOffsetSet()
    {
        $obj = new KeySpaceConstrainedParams();
        $obj->offsetSet("foo", "bar");
        $this->assertEquals("bar", $obj->params["foo"], "should the value be 'bar'");

        $obj = new KeySpaceConstrainedParams();
        $obj->offsetSet(null, "bar");
        $this->assertEquals("bar", $obj->params[0], "should the value be 'bar'");


        $this->setExpectedException("RangeException", "exceeded available parameter key space", 0);
        $obj = new KeySpaceConstrainedParams();
        $obj->offsetSet(str_repeat("a", 65538), "bar");

        $this->assertEquals(3, $obj->size, "should increase size by 3");
    }

    public function testArrayAccessSet()
    {
        $obj = new KeySpaceConstrainedParams();
        $obj["foo"] = "bar";
        $this->assertEquals("bar", $obj->params["foo"], "should the value be 'bar'");

        $obj = new KeySpaceConstrainedParams();
        $obj[] = "bar";
        $this->assertEquals("bar", $obj->params[0], "should the value be 'bar'");


        $this->setExpectedException("RangeException", "exceeded available parameter key space", 0);
        $obj = new KeySpaceConstrainedParams();
        $obj[str_repeat("a", 65538)] = "bar";

        $this->assertEquals(3, $obj->size, "should increase size by 3");
    }

    public function testOffsetUnset()
    {
        $obj = new KeySpaceConstrainedParams();
        $obj["foo"] = "bar";
        $obj->offsetUnset("foo");
        $this->assertFalse(isset($obj->params["foo"]), "should be undefined because of unset");
        $this->assertEquals(0, $obj->size, "should decrease size by 3");
    }
}
