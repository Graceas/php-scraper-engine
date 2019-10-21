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
    public function execute($storage)
    {
        $parseResults = array();
        /** @var ParserInterface $parser */
        $parser = $this->settings['parser'];
        /** @var ResponseInterface $response */
        foreach ($storage[$this->required[0]] as $response) {

            $parseResults[] = $parser->parse($response, array(
                'instructions' => $this->settings['instructions'],
            ));
        }

        return $parseResults;
    }
}
