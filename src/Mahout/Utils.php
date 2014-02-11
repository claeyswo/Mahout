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

use Mahout\Utils\KeySpaceConstrainedParams;

/**
 * Class Utils
 *
 * @package Mahout
 */
class Utils
{
    /**
     * The default number of bytes to allow parameter keys to take up.
     * This helps prevent a rogue client from flooding a Request.
     *
     * @var int
     */
    const KEY_SPACE_LIMIT = 65536;

    const DEFAULT_SEP = "/[&;] */";

    /**
     * @param $string
     *
     * @return string
     */
    public static function unescape($string)
    {
        if (preg_match("/\+/", $string)) {
            return urldecode($string);
        } else {
            return rawurldecode($string);
        }
    }

    /**
     * @param string $queryString
     * @param string $sep
     *
     * @return mixed
     */
    public static function parseNestedQuery($queryString, $sep = null)
    {
        $params = new KeySpaceConstrainedParams();

        $regex = $sep ? "/[".$sep."] */" : self::DEFAULT_SEP;

        foreach (preg_split($regex, $queryString) as $query) {
            $param = explode("=", $query, 2);
            $param = array_map("\Mahout\Utils::unescape", $param);
            $key = $param[0];
            $value = null;
            if (isset($param[1])) {
                $value = $param[1];
            }
            $params = self::normalizeParams($params, $key, $value);
        }

        return $params->toParamsHash();
    }

    /**
     * @param      $params
     * @param      $name
     * @param null $val
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public static function normalizeParams($params, $name, $val = null)
    {
        preg_match("/\A[\[\]]*([^\[\]]+)\]*([^$]*)/", $name, $matches);
        $key = $after = "";

        if (isset($matches[1])) {
            $key = $matches[1];
        }

        if (isset($matches[2])) {
            $after = $matches[2];
        }

        if (empty($key)) {
            return $params;
        }

        if ($after == "") {
            $params[$key] = $val;
        } elseif ($after == "[") {
            $params[$name] = $val;
        } elseif ($after == "[]") {
            if (!isset($params[$key])) {
                $params[$key] = array();
            }

            if (!is_array($params[$key])) {
                throw new \InvalidArgumentException(
                    "expected Array (got ".gettype($params[$key]).") for param `".$key."'"
                );
            }

            $params[$key][] = $val;
        } elseif (preg_match("/^\[\]\[([^\[\]]+)\]$/", $after, $matches) ||
            preg_match("/^\[\](.+)$/", $after, $matches)) {
            $childKey = $matches[1];
            if (!isset($params[$key])) {
                $params[$key] = array();
            }
            if (!is_array($params[$key])) {
                throw new \InvalidArgumentException(
                    "expected Array (got ".gettype($params[$key]).") for param `".$key."'"
                );
            }

            $counter = count($params[$key]) == 0 ? count($params[$key]) : count($params[$key]) - 1;
            $childParent = isset($params[$key][$counter]) ? $params[$key][$counter] : null;

            if (self::isParamsArrayType($childParent) &&
                !isset($childParent[$childKey])
            ) {
                $params[$key][$counter] = self::normalizeParams($params[$key][$counter], $childKey, $val);
            } else {
                $params[$key][] = self::normalizeParams(new KeySpaceConstrainedParams(), $childKey, $val);
            }
        } else {
            if (!isset($params[$key])) {
                $params[$key] = new KeySpaceConstrainedParams();
            }

            if (!self::isParamsArrayType($params[$key])) {
                throw new \InvalidArgumentException(
                    "expected Array (got ".gettype($params[$key]).") for param `".$key."'"
                );
            }

            $params[$key] = self::normalizeParams($params[$key], $after, $val);
        }

        return $params;
    }

    /**
     * @param $obj
     *
     * @return bool
     */
    public static function isParamsArrayType($obj)
    {
        return ($obj instanceof KeySpaceConstrainedParams || is_array($obj));
    }
}
