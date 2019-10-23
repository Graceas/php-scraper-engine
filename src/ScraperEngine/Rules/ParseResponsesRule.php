<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 23:58
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Loader\Response\ResponseInterface;
use ScraperEngine\Parser\ParserInterface;

/**
 * Class ParseResponsesRule
 * @package ScraperEngine\Rules
 */
class ParseResponsesRule extends BaseRule
{
    /**
     * settings:
     * parser - class implemented ParserInterface
     * @param array $storage
     * @return mixed
     */
    public function execute(&$storage)
    {
        $parseResults = array();
        /** @var ParserInterface $parser */
        $parser = $this->settings['parser'];
        /** @var ResponseInterface $response */

        $tempPath  = isset($this->settings['temp_path']) ? $this->settings['temp_path'] : sys_get_temp_dir().'/';
        $responses = &$storage[$this->required[0]];
        foreach ($responses as &$response) {

            $parseResults[] = $parser->parse($response, array(
                'instructions' => $this->settings['instructions'],
                'temp_path'    => $tempPath
            ));

            $filepath = null;
            $response = null;
        }


        $responses = null;
        $storage[$this->required[0]] = null;
        $parser   = null;
        $tempPath = null;

        return $parseResults;
    }
}
