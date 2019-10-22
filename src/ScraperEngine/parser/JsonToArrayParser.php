<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 00:33
 */

namespace ScraperEngine\Parser;

use ScraperEngine\Exception\ScraperEngineException;
use ScraperEngine\Loader\Response\ResponseInterface;


/**
 * Class JsonToArrayParser
 * @package ScraperEngine\Parser
 */
class JsonToArrayParser implements ParserInterface
{
    /**
     * @param string $content
     * @param array  $settings [instructions => '']
     * @return array
     * @throws ScraperEngineException
     */
    public function parse($content, $settings = array())
    {
        $sourceUrl = 'unknown';
        if ($content instanceof ResponseInterface) {
            $sourceUrl = $content->getRequest()->getUrl();
            $content = $content->getBody();
        }

        if (is_string($content)) {
            $content = json_decode($content, true);
        }

        $result = array();
        $instructions = explode(PHP_EOL, $settings['instructions']);

        foreach ($instructions as $instruction) {
            if (strpos($instruction, ':->') === false) {
                continue;
            }

            list($instructionKey, $instruction) = explode(':->', $instruction, 2);
            @list($instruction, $default) = explode('||', $instruction, 2);
            $keys = explode('->', $instruction);

            $last = $content;
            foreach ($keys as $key) {
                if (isset($last[$key])) {
                    $last = $last[$key];
                } else {
                    if ($default != null) {
                        $last = $default;
                        break;
                    } else {
                        throw new ScraperEngineException(printf('Field %s is not exists in the %s', $key, print_r($last, true)));
                    }
                }
            }

            $result[$instructionKey] = $last;
        }

        $result = array_merge($result, array(
            '_source_url'  => $sourceUrl,
            '_loaded_date' => time()
        ));

        return $result;
    }
}
