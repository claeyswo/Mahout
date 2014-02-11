<?php
/*
 * This file is part of the Mahout package.
 *
 * (c) Aurimas Niekis <aurimas.niekis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mahout\Tests\Functional;

use \Mahout\Utils;

/**
 * Class UtilsTest
 *
 * @package Mahout\Tests\Functional
 */
class UtilsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getParseNestedQueryData()
    {
        $out = array();

        //Case 0
        $out[] = array("foo", array("foo" => null));

        //Case 1
        $out[] = array("foo=", array("foo" => ""));

        //Case 2
        $out[] = array("foo=bar", array("foo" => "bar"));

        //Case 3
        $out[] = array("foo=\"bar\"", array("foo" => "\"bar\""));

        //Case 4
        $out[] = array("foo=bar&foo=quux", array("foo" => "quux"));

        //Case 5
        $out[] = array("foo&foo=", array("foo" => ""));

        //Case 6
        $out[] = array("foo=1&bar=2", array("foo" => "1", "bar" => "2"));

        //Case 7
        $out[] = array("&foo=1&&bar=2", array("foo" => "1", "bar" => "2"));

        //Case 8
        $out[] = array("foo&bar=", array("foo" => null, "bar" => ""));

        //Case 9
        $out[] = array("foo=bar&baz=", array("foo" => "bar", "baz" => ""));

        //Case 10
        $out[] = array("my+weird+field=q1%212%22%27w%245%267%2Fz8%29%3F",
            array("my weird field" => "q1!2\"'w$5&7/z8)?"));

        //Case 11
        $out[] = array("a=b&pid%3D1234=1023", array("pid=1234" => "1023", "a" => "b"));

        //Case 12
        $out[] = array("foo[]", array("foo" => array(null)));

        //Case 13
        $out[] = array("foo[]=", array("foo" => array("")));

        //Case 14
        $out[] = array("foo[]=bar", array("foo" => array("bar")));

        //Case 15
        $out[] = array("foo[]=bar&foo", array("foo" => null));

        //Case 16
        $out[] = array("foo[]=bar&foo[", array("foo" => array("bar"), "foo[" => null));

        //Case 17
        $out[] = array("foo[]=bar&foo[=baz", array("foo" => array("bar"), "foo[" => "baz"));

        //Case 18
        $out[] = array("foo[]=bar&foo[]", array("foo" => array("bar", null)));

        //Case 19
        $out[] = array("foo[]=bar&foo[]=", array("foo" => array("bar", "")));

        //Case 20
        $out[] = array("foo[]=1&foo[]=2", array("foo" => array("1", "2")));

        //Case 21
        $out[] = array("foo=bar&baz[]=1&baz[]=2&baz[]=3", array("foo" => "bar", "baz" => array("1", "2", "3")));

        //Case 22
        $out[] = array("foo[]=bar&baz[]=1&baz[]=2&baz[]=3",
            array("foo" => array("bar"), "baz" => array("1", "2", "3")));

        //Case 23
        $out[] = array("x[y][z]=1", array("x" => array("y" => array("z" => "1"))));

        //Case 24
        $out[] = array("x[y][z][]=1", array("x" => array("y" => array("z" => array("1")))));

        //Case 25
        $out[] = array("x[y][z]=1&x[y][z]=2", array("x" => array("y" => array("z" => "2"))));

        //Case 26
        $out[] = array("x[y][z][]=1&x[y][z][]=2", array("x" => array("y" => array("z" => array("1", "2")))));

        //Case 27
        $out[] = array("x[y][][z]=1", array("x" => array("y" => array(array("z" => "1")))));

        //Case 28
        $out[] = array("x[y][][z][]=1", array("x" => array("y" => array(array("z" => array("1"))))));

        //Case 29
        $out[] = array("x[y][][z]=1&x[y][][w]=2", array("x" => array("y" => array(array("z" => "1", "w" => "2")))));

        //Case 30
        $out[] = array("x[y][][v][w]=1", array("x" => array("y" => array(array("v" => array("w" => "1"))))));

        //Case 31
        $out[] = array("x[y][][z]=1&x[y][][v][w]=2",
            array("x" => array("y" => array(array("z" => "1", "v" => array("w" => "2"))))));

        //Case 32
        $out[] = array("x[y][][z]=1&x[y][][z]=2",
            array("x" => array("y" => array(array("z" => "1"), array("z" => "2")))));

        //Case 33
        $out[] = array("x[y][][z]=1&x[y][][w]=a&x[y][][z]=2&x[y][][w]=3",
            array("x" => array("y" => array(array("z" => "1", "w" => "a"), array("z" => "2", "w" => "3")))));

        //Case 34
        $out[] = array("x[y]=1&x[y]z=2",
            null, array("\InvalidArgumentException", "expected Array (got string) for param `y'", 0));

        //Case 35
        $out[] = array("x[y]=1&x[]=1",
            null, array("\InvalidArgumentException", "expected Array (got object) for param `x'", 0));

        //Case 36
        $out[] = array("x[y]=1&x[y][][w]=2",
            null, array("\InvalidArgumentException", "expected Array (got string) for param `y'", 0));


        return $out;
    }

    /**
     * @dataProvider getParseNestedQueryData
     *
     * @param      $given
     * @param      $expected
     * @param null $expectedException
     */
    public function testParseNestedQuery($given, $expected, $expectedException = null)
    {
        if (!$expectedException) {
            $this->assertEquals($expected, Utils::parseNestedQuery($given));
        } else {
            $this->setExpectedException($expectedException[0], $expectedException[1], $expectedException[2]);
            Utils::parseNestedQuery($given);
        }

    }
}
