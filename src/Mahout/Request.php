<?php
/*
 * This file is part of the Mahout package.
 *
 * (c) Aurimas Niekis <aurimas.niekis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mahout;

/**
 * Class Request
 *
 * @package Mahout
 */
class Request
{
    /**
     * @var array
     */
    public $env;

    /**
     * Default ports depending on scheme. Used to decide whether or not
     * to include the port in a generated URI.
     *
     * @var array
     */
    public static $defaultPorts = array("http" => 80, "https" => 443);

    /**
     * The set of form-data media-types. Requests that do not indicate
     * one of the media types presents in this list will not be eligible
     * for form-data / param parsing.
     *
     * @var array
     */
    public static $formDataMediaTypes = array(
        'application/x-www-form-urlencoded',
        'multipart/form-data'
    );

    /**
     * The set of media-types. Requests that do not indicate
     * one of the media types presents in this list will not be eligible
     * for param parsing like soap attachments or generic multiparts
     *
     * @var array
     */
    public static $parseableDataTypes = array(
        'multipart/related',
        'multipart/mixed'
    );

    /**
     * @param array $env
     */
    public function __construct($env)
    {
        $this->env = $env;
    }

    /**
     * @return mixed
     */
    public function body()
    {
        return $this->env['mahout.input'];
    }

    /**
     * @return string
     */
    public function scriptName()
    {
        return (string)$this->env['SCRIPT_NAME'];
    }

    /**
     * @return string
     */
    public function pathInfo()
    {
        return (string)$this->env['PATH_INFO'];
    }

    /**
     * @return mixed
     */
    public function requestMethod()
    {
        return $this->env['REQUEST_METHOD'];
    }

    /**
     * @return string
     */
    public function queryString()
    {
        return (string)$this->env['QUERY_STRING'];
    }

    /**
     * @return mixed
     */
    public function contentLength()
    {
        return $this->env['CONTENT_LENGTH'];
    }

    /**
     * @return string|null
     */
    public function contentType()
    {
        if (isset($this->env['CONTENT_TYPE']) || !empty($this->env['CONTENT_TYPE'])) {
            return $this->env['CONTENT_TYPE'];
        } else {
            return null;
        }
    }

    /**
     * @return array
     */
    public function session()
    {
        if ($this->env['mahout.session']) {
            return $this->env['mahout.session'];
        }

        return array();
    }

    /**
     * @return array
     */
    public function sessionOptions()
    {
        if ($this->env['mahout.session.options']) {
            return $this->env['mahout.session.options'];
        }

        return array();
    }

    /**
     * @return mixed
     */
    public function logger()
    {
        return $this->env['mahout.logger'];
    }


    /**
     * The media type (type/subtype) portion of the CONTENT_TYPE header
     * without any media type parameters. e.g., when CONTENT_TYPE is
     * "text/plain;charset=utf-8", the media-type is "text/plain".
     *
     * For more information on the use of media types in HTTP, see:
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.7
     *
     * @return null|string
     */
    public function mediaType()
    {
        if ($this->contentType()) {
            $mediaType = preg_split("/\s*[;,]\s*/", $this->contentType(), 2);
            return strtolower($mediaType[0]);
        }

        return null;
    }

    /**
     * The media type parameters provided in CONTENT_TYPE as a Array, or
     * an empty Array if no CONTENT_TYPE or media-type parameters were
     * provided.  e.g., when the CONTENT_TYPE is "text/plain;charset=utf-8",
     * this method responds with the following Array:
     *   array( 'charset' => 'utf-8' )
     *
     * @return array
     */
    public function mediaTypeParams()
    {
        if ($this->contentType()) {
            $mediaType = preg_split("/\s*[;,]\s*/", $this->contentType());
            array_shift($mediaType);
            $mediaTypeParams = array();
            foreach ($mediaType as $params) {
                $params = explode("=", $params, 2);
                $key = strtolower($params[0]);
                $mediaTypeParams[$key] = $params[1];
            }

            return $mediaTypeParams;
        } else {
            return array();
        }
    }

    /**
     * @param $key
     *
     * @return null
     */
    public function contentCharset($key)
    {
        $mediaTypeParams = $this->mediaTypeParams();
        if ($mediaTypeParams && isset($mediaTypeParams[$key])) {
            return $mediaTypeParams[$key];
        } else {
            return null;
        }
    }

    /**
     * @return bool|string
     */
    public function scheme()
    {
        if (isset($this->env['HTTPS']) && $this->env['HTTPS'] == "on") {
            return "https";
        } elseif (isset($this->env['HTTP_X_FORWARDED_SSL']) && $this->env['HTTP_X_FORWARDED_SSL'] == "on") {
            return "https";
        } elseif (isset($this->env['HTTP_X_FORWARDED_SCHEME'])) {
            return $this->env['HTTP_X_FORWARDED_SCHEME'];
        } elseif (isset($this->env['HTTP_X_FORWARDED_PROTO'])) {
            $proto = $this->env['HTTP_X_FORWARDED_PROTO'];
            $proto = explode(",", $proto);
            return $proto[0];
        } elseif (isset($this->env['mahout.url_scheme'])) {
            return $this->env['mahout.url_scheme'];
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isSSL()
    {
        return $this->scheme() == "https";
    }

    /**
     * @return string
     */
    public function hostWithPort()
    {
        if (isset($this->env['HTTP_X_FORWARDED_HOST']) && ($forwarded = $this->env['HTTP_X_FORWARDED_HOST'])) {
            $forwarded = preg_split("/,\s?/", $forwarded);
            return array_pop($forwarded);
        } else {
            if (isset($this->env['HTTP_HOST'])) {
                return $this->env['HTTP_HOST'];
            } elseif (isset($this->env['SERVER_NAME']) && isset($this->env['SERVER_PORT'])) {
                return $this->env['SERVER_NAME'] . ":" . $this->env['SERVER_PORT'];
            } elseif (isset($this->env['SERVER_ADDR']) && isset($this->env['SERVER_PORT'])) {
                return $this->env['SERVER_ADDR'] . ":" . $this->env['SERVER_PORT'];
            }
        }

        return null;
    }

    /**
     * @return int|null
     */
    public function port()
    {
        $port = explode(":", $this->hostWithPort());
        if (isset($port[1]) && $port[1]) {
            return (int)$port[1];
        } elseif (isset($this->env['HTTP_X_FORWARDED_PORT'])) {
            return (int)$this->env['HTTP_X_FORWARDED_PORT'];
        } elseif (isset($this->env['HTTP_X_FORWARDED_HOST'])) {
            return self::$defaultPorts[$this->scheme()];
        } elseif (isset($this->env['HTTP_X_FORWARDED_PROTO'])) {
            return self::$defaultPorts[$this->env['HTTP_X_FORWARDED_PROTO']];
        } elseif (isset($this->env['SERVER_PORT'])) {
            return (int)$this->env['SERVER_PORT'];
        } else {
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function host()
    {
        return preg_replace("/:\d+\z/", "", (string)$this->hostWithPort());
    }

    /**
     * @param $name
     */
    public function setScriptName($name)
    {
        $this->env['SCRIPT_NAME'] = (string)$name;
    }

    /**
     * @param $path
     */
    public function setPathInfo($path)
    {
        $this->env['PATH_INFO'] = (string)$path;
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        return $this->requestMethod() == "DELETE";
    }

    /**
     * @return bool
     */
    public function isGet()
    {
        return $this->requestMethod() == "GET";
    }

    /**
     * @return bool
     */
    public function isHead()
    {
        return $this->requestMethod() == "HEAD";
    }

    /**
     * @return bool
     */
    public function isOptions()
    {
        return $this->requestMethod() == "OPTIONS";
    }

    /**
     * @return bool
     */
    public function isLink()
    {
        return $this->requestMethod() == "LINK";
    }

    /**
     * @return bool
     */
    public function isPatch()
    {
        return $this->requestMethod() == "PATCH";
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        return $this->requestMethod() == "POST";
    }

    /**
     * @return bool
     */
    public function isPut()
    {
        return $this->requestMethod() == "PUT";
    }

    /**
     * @return bool
     */
    public function isTrace()
    {
        return $this->requestMethod() == "TRACE";
    }

    /**
     * @return bool
     */
    public function isUnlink()
    {
        return $this->requestMethod() == "UNLINK";
    }

    /**
     * @return bool
     */
    public function isFormData()
    {
        $meth = null;
        $type = $this->mediaType();
        if (isset($this->env['mahout.methodoverride.original_method'])) {
            $meth = $this->env['mahout.methodoverride.original_method'];
        } elseif (isset($this->env['REQUEST_METHOD'])) {
            $meth = $this->env['REQUEST_METHOD'];
        }

        return ($meth == "POST" && $type == null) || in_array($type, self::$formDataMediaTypes);
    }

    /**
     * @return bool
     */
    public function isParseableData()
    {
        return in_array($this->mediaType(), self::$parseableDataTypes);
    }
}
