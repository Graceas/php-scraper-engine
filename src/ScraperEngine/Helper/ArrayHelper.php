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
}
