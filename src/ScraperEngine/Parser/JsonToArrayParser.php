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
use ScraperEngine\Result\Result;


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
    public function parse($content, $settings = array())
    {
        $sourceUrl = 'unknown';
        if ($content instanceof ResponseInterface) {
            $response  = $content;

            $content   = $response->getBody();
            $sourceUrl = $response->getInfo()['requested_url'];

            $response = null;
            $request  = null;
        }

        $data = array();
        if (is_string($content)) {
            $data    = json_decode($content, true);
            $content = null;
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

            $last = $data;
            foreach ($keys as $key) {
                if (isset($last[$key])) {
                    $last = $last[$key];
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
        }

        $instructions = null;
        $data         = null;
        $settings     = null;

        $merged       = array_merge($result, array(
            '_source_url'  => $sourceUrl,
            '_loaded_date' => time()
        ));

        $sourceUrl = null;
        $result    = null;

        $tempPath  = isset($settings['temp_path']) ? $settings['temp_path'] : sys_get_temp_dir().'/';
        $filepath  = $tempPath.'_res_'.sha1(microtime(true).microtime().rand(0, 999999));
        $result    = new Result($filepath, $merged);
        $tempPath  = null;
        $merged    = null;
        $filepath  = null;

        return $result;
    }
}
