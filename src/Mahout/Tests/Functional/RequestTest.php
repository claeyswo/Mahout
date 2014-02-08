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

use Mahout\Request;

/**
 * Class RequestTest
 *
 * @package Mahout\Tests\Functional
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $env
     *
     * @return Request
     */
    public function getRequestObject($env)
    {
        return new Request($env);
    }

    public function testConstructor()
    {
        $env     = array("foo" => "bar");
        $request = $this->getRequestObject($env);

        $this->assertEquals($env, $request->env);
    }

    /**
     * @return array
     */
    public function getBasicFieldData()
    {
        $out = array();

        //CASE 0 Checking body
        $out[] = array("body", array(), array("mahout.input" => "foo"), "foo");

        //CASE 1 Checking scriptName
        $out[] = array("scriptName", array(), array("SCRIPT_NAME" => "bar"), "bar");

        //CASE 2 Checking requestMethod
        $out[] = array("requestMethod", array(), array("REQUEST_METHOD" => "foo"), "foo");

        //CASE 3 Checking queryString
        $out[] = array("queryString", array(), array("QUERY_STRING" => "foo"), "foo");

        //CASE 4 Checking contentLength
        $out[] = array("contentLength", array(), array("CONTENT_LENGTH" => "bar"), "bar");

        //CASE 5 Checking session
        $out[] = array("session", array(), array("mahout.session" => "foo"), "foo");

        //CASE 6 Checking session
        $out[] = array("session", array(), array("mahout.session" => ""), array());

        //CASE 7 Checking session
        $out[] = array("session", array(), array("mahout.session" => null), array());

        //CASE 8 Checking sessionOptions
        $out[] = array("sessionOptions", array(), array("mahout.session.options" => "bar"), "bar");

        //CASE 9 Checking sessionOptions
        $out[] = array("sessionOptions", array(), array("mahout.session.options" => ""), array());

        //CASE 10 Checking sessionOptions
        $out[] = array("sessionOptions", array(), array("mahout.session.options" => null), array());

        //CASE 11 Checking sessionOptions
        $out[] = array("logger", array(), array("mahout.logger" => "foo"), "foo");

        //CASE 12 Checking contentType
        $out[] = array(
            "contentType",
            array(),
            array("CONTENT_TYPE" => "text/html; charset=UTF-8"),
            "text/html; charset=UTF-8"
            );

        //CASE 13 Checking contentType
        $out[] = array("contentType", array(), array("CONTENT_TYPE" => ""), null);

        //CASE 14 Checking contentType
        $out[] = array("contentType", array(), array("CONTENT_TYPE" => null), null);

        //CASE 15 Checking mediaType
        $out[] = array("mediaType", array(), array("CONTENT_TYPE" => "text/html; charset=UTF-8"), "text/html");

        //CASE 16 Checking mediaType
        $out[] = array("mediaType", array(), array("CONTENT_TYPE" => ""), null);

        //CASE 17 Checking pathInfo
        $out[] = array("pathInfo", array(), array("PATH_INFO" => "bar"), "bar");

        //CASE 18 Checking mediaTypeParams
        $out[] = array(
            "mediaTypeParams",
            array(),
            array("CONTENT_TYPE" => "text/html; charset=UTF-8"),
            array("charset" => "UTF-8")
        );

        //CASE 19 Checking mediaTypeParams
        $out[] = array("mediaTypeParams", array(), array("CONTENT_TYPE" => null), array());

        //CASE 20 Checking contentCharset
        $out[] = array(
            "contentCharset",
            array('charset'),
            array("CONTENT_TYPE" => "text/html; charset=UTF-8"),
            "UTF-8"
        );

        //CASE 21 Checking contentCharset
        $out[] = array(
            "contentCharset",
            array('charset'),
            array("CONTENT_TYPE" => null),
            null
        );

        //CASE 22 Checking scheme
        $out[] = array(
            "scheme",
            array(),
            array("HTTPS" => "on"),
            "https"
        );

        //CASE 23 Checking scheme
        $out[] = array(
            "scheme",
            array(),
            array("HTTP_X_FORWARDED_SSL" => "on"),
            "https"
        );

        //CASE 24 Checking scheme
        $out[] = array(
            "scheme",
            array(),
            array("HTTP_X_FORWARDED_SCHEME" => "foo"),
            "foo"
        );

        //CASE 25 Checking scheme
        $out[] = array(
            "scheme",
            array(),
            array("HTTP_X_FORWARDED_PROTO" => "foo,bar"),
            "foo"
        );

        //CASE 26 Checking scheme
        $out[] = array(
            "scheme",
            array(),
            array("mahout.url_scheme" => "http"),
            "http"
        );

        //CASE 27 Checking scheme
        $out[] = array(
            "scheme",
            array(),
            array(),
            null
        );

        //CASE 28 Checking isSSL
        $out[] = array(
            "isSSL",
            array(),
            array("HTTPS" => "on"),
            true
        );

        //CASE 29 Checking hostWithPort
        $out[] = array(
            "hostWithPort",
            array(),
            array("HTTP_X_FORWARDED_HOST" => "a,b"),
            "b"
        );

        //CASE 30 Checking hostWithPort
        $out[] = array(
            "hostWithPort",
            array(),
            array(
                "HTTP_HOST" => "localhost"
            ),
            "localhost"
        );

        //CASE 31 Checking hostWithPort
        $out[] = array(
            "hostWithPort",
            array(),
            array(
                "SERVER_NAME" => "localhost",
                "SERVER_PORT" => "8080"
            ),
            "localhost:8080"
        );

        //CASE 32 Checking hostWithPort
        $out[] = array(
            "hostWithPort",
            array(),
            array(
                "SERVER_ADDR" => "localhost",
                "SERVER_PORT" => "8080"
            ),
            "localhost:8080"
        );

        //CASE 33 Checking hostWithPort
        $out[] = array(
            "hostWithPort",
            array(),
            array(),
            null
        );

        //CASE 34 Checking port
        $out[] = array(
            "port",
            array(),
            array(
                "SERVER_ADDR" => "localhost",
                "SERVER_PORT" => "8080"
            ),
            8080
        );

        //CASE 35 Checking port
        $out[] = array(
            "port",
            array(),
            array(
                "HTTP_X_FORWARDED_PORT" => "8080"
            ),
            8080
        );

        //CASE 36 Checking port
        $out[] = array(
            "port",
            array(),
            array(
                "HTTP_X_FORWARDED_HOST" => "localhost",
                "HTTP_X_FORWARDED_PROTO" => "https"
            ),
            443
        );

        //CASE 37 Checking port
        $out[] = array(
            "port",
            array(),
            array(
                "HTTP_X_FORWARDED_PROTO" => "http"
            ),
            80
        );

        //CASE 38 Checking port
        $out[] = array(
            "port",
            array(),
            array(
                "SERVER_PORT" => "8080"
            ),
            8080
        );

        //CASE 39 Checking port
        $out[] = array(
            "port",
            array(),
            array(),
            null
        );

        //CASE 40 Checking host
        $out[] = array(
            "host",
            array(),
            array(
                "SERVER_ADDR" => "localhost",
                "SERVER_PORT" => "8080"
            ),
            "localhost"
        );

        //CASE 41 Checking isDelete
        $out[] = array(
            "isDelete",
            array(),
            array(
                "REQUEST_METHOD" => "DELETE"
            ),
            true
        );

        //CASE 42 Checking isGet
        $out[] = array(
            "isGet",
            array(),
            array(
                "REQUEST_METHOD" => "GET"
            ),
            true
        );

        //CASE 43 Checking isHead
        $out[] = array(
            "isHead",
            array(),
            array(
                "REQUEST_METHOD" => "HEAD"
            ),
            true
        );

        //CASE 44 Checking isOptions
        $out[] = array(
            "isOptions",
            array(),
            array(
                "REQUEST_METHOD" => "OPTIONS"
            ),
            true
        );

        //CASE 45 Checking isLink
        $out[] = array(
            "isLink",
            array(),
            array(
                "REQUEST_METHOD" => "LINK"
            ),
            true
        );

        //CASE 46 Checking isPatch
        $out[] = array(
            "isPatch",
            array(),
            array(
                "REQUEST_METHOD" => "PATCH"
            ),
            true
        );

        //CASE 47 Checking isPost
        $out[] = array(
            "isPost",
            array(),
            array(
                "REQUEST_METHOD" => "POST"
            ),
            true
        );

        //CASE 48 Checking isPut
        $out[] = array(
            "isPut",
            array(),
            array(
                "REQUEST_METHOD" => "PUT"
            ),
            true
        );

        //CASE 49 Checking isTrace
        $out[] = array(
            "isTrace",
            array(),
            array(
                "REQUEST_METHOD" => "TRACE"
            ),
            true
        );

        //CASE 50 Checking isUnlink
        $out[] = array(
            "isUnlink",
            array(),
            array(
                "REQUEST_METHOD" => "UNLINK"
            ),
            true
        );

        //CASE 51 Checking isFormData
        $out[] = array(
            "isFormData",
            array(),
            array(
                "mahout.methodoverride.original_method" => "POST"
            ),
            true
        );

        //CASE 52 Checking isFormData
        $out[] = array(
            "isFormData",
            array(),
            array(
                "REQUEST_METHOD" => "POST"
            ),
            true
        );

        //CASE 53 Checking isFormData
        $out[] = array(
            "isFormData",
            array(),
            array(
                "CONTENT_TYPE" => "application/x-www-form-urlencoded"
            ),
            true
        );

        //CASE 54 Checking isFormData
        $out[] = array(
            "isFormData",
            array(),
            array(
                "CONTENT_TYPE" => "multipart/form-data"
            ),
            true
        );

        //CASE 55 Checking isFormData
        $out[] = array(
            "isFormData",
            array(),
            array(),
            false
        );

        //CASE 56 Checking isParseableData
        $out[] = array(
            "isParseableData",
            array(),
            array(
                "CONTENT_TYPE" => "multipart/related"
            ),
            true
        );

        //CASE 57 Checking isParseableData
        $out[] = array(
            "isParseableData",
            array(),
            array(
                "CONTENT_TYPE" => "multipart/mixed"
            ),
            true
        );

        //CASE 58 Checking isParseableData
        $out[] = array(
            "isParseableData",
            array(),
            array(),
            false
        );

        return $out;
    }

    /**
     * @dataProvider getBasicFieldData
     * @param $method
     * @param $args
     * @param $env
     * @param $expected
     */
    public function testBasicFieldData($method, $args, $env, $expected)
    {
        $request = $this->getRequestObject($env);

        $this->assertEquals($expected, call_user_func_array(array($request,$method), $args));
    }

    /**
     * @return array
     */
    public function getBasicFieldDataModifier()
    {
        $out = array();

        // CASE 0 Checking setScriptName
        $out[] = array(
            "setScriptName",
            array("FooBar"),
            array("SCRIPT_NAME" => "FooBar")
        );

        // CASE 1 Checking setPathInfo
        $out[] = array(
            "setPathInfo",
            array("FooBar"),
            array("PATH_INFO" => "FooBar")
        );

        return $out;
    }

    /**
     * @dataProvider getBasicFieldDataModifier
     * @param $method
     * @param $args
     * @param $expected
     */
    public function testBasicFieldDataModifier($method, $args, $expected)
    {
        $request = $this->getRequestObject(array());

        call_user_func_array(array($request,$method), $args);

        $this->assertEquals($expected, $request->env);
    }
}
