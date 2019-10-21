<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 01:36
 */

namespace ScraperEngine\Formatter;


/**
 * Class ArrayToFlatArrayFormatter
 * @package ScraperEngine\Formatter
 */
class ArrayToFlatArrayFormatter implements FormatterInterface
{
    /**
     * @param array $input
     * @return array
     */
    public function format($input)
    {
        $formattedResults = array();

        foreach ($input as $items) {
            $systemKeyValues = array();
            foreach ($items as $key => $item) {
                if ($key[0] == '_') {
                    $systemKeyValues[$key] = $item;
                }
            }

            foreach ($items as $key => $item) {
                if ($key[0] == '_') {
                    continue;
                }
                $formattedResults[] = array_merge($item, $systemKeyValues);
            }
        }

        return $formattedResults;
    }
}
