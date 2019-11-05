<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-22
 * Time: 18:02
 */

namespace ScraperEngine\Helper;

use ScraperEngine\Result\Result;

/**
 * Class StringHelper
 * @package ScraperEngine\Helper
 */
class StringHelper
{
    /**
     * $m = 'test{aa}test2{bb}test3{%g_aa}'
     * $values = ["aa" => 123, "bb" = 456]
     * $globalValues = ["aa" => 890]
     * $r = replaceValues($m, $values, $globalValues)
     * > $r = test123test2456test3890
     * @param string $string
     * @param array  $values
     * @param array  $globalValues
     * @return mixed
     */
    public static function replaceValues($string, &$values, &$globalValues)
    {
        preg_match_all('/{(.*)}/U', $string, $matches);

        $data = null;
        if ($values instanceof Result) {
            $data      = $values->get();
        }

        foreach ($matches[1] as $key) {
            $global = strpos($key, '%g_') !== false;
            $regularKey = str_replace('%g_', '', $key);

            $value = ($global) ? $globalValues[$regularKey] : (($data) ? $data[$key] : $values[$key]);

            if ($value instanceof Result) {
                $value = $value->get();
            }

            $string = str_replace('{'.$key.'}', $value, $string);
        }

        $matches = null;
        $data    = null;
        unset($matches, $data);

        return $string;
    }
}
