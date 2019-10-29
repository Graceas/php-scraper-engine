<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-22
 * Time: 18:02
 */

namespace ScraperEngine\Helper;

/**
 * Class ArrayHelper
 * @package ScraperEngine\Helper
 */
class ArrayHelper
{
    /**
     * $m = [['a', 1], ['b', 2], ['c', 3]]
     * $r = getValueByFirstKey($m, 'b', null)
     * > $r = 2
     * $r = getValueByFirstKey($m, 'g', null)
     * > $r = null
     * @param array  $values
     * @param string $keyName
     * @param mixed  $default
     * @return mixed
     */
    public static function getValueByFirstKey($values, $keyName, $default = null)
    {
        $filtered = array_filter($values, function ($element) use ($keyName) { return $element[0] == $keyName; });

        if (count($filtered) == 0) {
            return $default;
        }

        $sliced = array_slice($filtered, 0, 1);

        if (!isset($sliced[0]) || !isset($sliced[0][1])) {
            return $default;
        }

        return $sliced[0][1];
    }

    /**
     * $array = ['a' => ['b' => 1, 'c' => 2]]
     * $path  = ['a', 'b']
     * $r = getValueByKeys($array, $path)
     * > $r = 1
     * @param array $array
     * @param array $path
     * @param mixed $default
     * @return null
     */
    public static function getValueByKeys(&$array, $path, $default = null) {
        $target = &$array;

        foreach($path as $key) {
            if (!$key) {
                return $default;
            }

            if (array_key_exists($key, $target)) {
                $target = &$target[$key];
            } else {
                return $default;
            }
        }

        return $target;
    }
}
