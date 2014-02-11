<?php
/*
 * This file is part of the Mahout package.
 *
 * (c) Aurimas Niekis <aurimas.niekis@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mahout\Utils;

use Mahout\Utils;

/**
 * Class KeySpaceConstrainedParams
 *
 * @package Mahout\Utils
 */
class KeySpaceConstrainedParams implements \ArrayAccess
{
    public $nullGuard = null;

    /**
     * @var int
     */
    public $limit;

    /**
     * @var int
     */
    public $size;

    /**
     * @var array
     */
    public $data;

    /**
     * @param int $limit
     */
    public function __construct($limit = Utils::KEY_SPACE_LIMIT)
    {
        $this->limit = $limit;
        $this->size = 0;
        $this->params = array();
    }

    /**
     * @return array
     */
    public function toParamsHash()
    {
        $hash = $this->params;

        array_walk_recursive($hash, function (&$cValue) {
            if ($cValue instanceof KeySpaceConstrainedParams) {
                $cValue = $cValue->toParamsHash();
            }
        });

        return $hash;
    }

    /**
     * @param $offset
     *
     * @return null
     */
    public function &offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->params[$offset];
        } else {
            return $this->nullGuard;
        }
    }

    /**
     * @param $offset
     * @param $value
     * @throws \RangeException
     */
    public function offsetSet($offset, $value)
    {
        if (!is_null($offset)) {
            $this->size += strlen($offset);
            if ($this->size > $this->limit) {
                throw new \RangeException("exceeded available parameter key space");
            } else {
                $this->params[$offset] = $value;
            }
        } else {
            $this->params[] = $value;
        }
    }

    /**
     * @param $offset
     *
     * @throws \RangeException
     */
    public function offsetUnset($offset)
    {
        if ($offset && $this->offsetExists($offset)) {
            $this->size -= strlen($offset);
            unset($this->params[$offset]);
        }
    }

    /**
     * @param $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->params[$offset]);
    }
}
