<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 00:33
 */

namespace ScraperEngine\Parser;

use ScraperEngine\Exception\ScraperEngineException;

/**
 * Class JsonToArrayParser
 * @package ScraperEngine\Parser
 */
class JsonToArrayParser implements ParserInterface
{
    /**
     * @param string $content
     * @param array  $settings [instructions => '']
     * @return mixed
     * @throws ScraperEngineException
     */
    public function &parse(&$content, $settings = array())
    {
        $data = json_decode($content, true);

        $content = null;
        unset($content);

        $result = array();
        $instructions = $settings['instructions'];

        foreach ($instructions as $instruction) {
            if (strpos($instruction, ':->') === false) {
                continue;
            }

            list($instructionKey, $instruction) = explode(':->', $instruction, 2);
            @list($instruction, $default) = explode('||', $instruction, 2);
            $keys = explode('->', $instruction);

            $last = $data;
            foreach ($keys as $key) {
                if (isset($last[$key])) {
                    $last = &$last[$key];
                } else {
                    if ($default != null) {
                        $last = $default;
                        break;
                    } else {
                        throw new ScraperEngineException(sprintf('Field %s is not exists in the %s', $key, print_r($last, true)));
                    }
                }
            }

            $result[$instructionKey] = $last;

            $last           = null;
            $keys           = null;
            $instructionKey = null;
            $instruction    = null;
            $default        = null;

            unset($last, $keys, $instructionKey, $instruction, $default);
        }

        $instructions  = null;
        $data          = null;
        $settings      = null;
        unset($instructions, $data, $settings);

        return $result;
    }
}
