<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 23:58
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Helper\FileHelper;
use ScraperEngine\Loader\Response\ResponseInterface;
use ScraperEngine\Parser\ParserInterface;
use ScraperEngine\Result\Result;
use ScraperEngine\Scraper;

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
        $this->logger->addInfo('>>>>test0');
        $responses = &$storage[$this->required[0]];

        $instructions = explode(PHP_EOL, $this->settings['instructions']);
        $settings     = array(
            'instructions' => $instructions,
        );

        foreach ($responses as &$response) {
            $sourceUrl = $response->getRequestedUrl();

            $responsePath = $response->getResponsePath();

            $fileContent      = FileHelper::readFile($responsePath);
            $originalResponse = unserialize($fileContent);
            $body             = $originalResponse->getBody();

            $responsePath = null;
            $originalResponse = null;
            $fileContent = null;

            unset($responsePath, $originalResponse, $fileContent);

            $settings['_source_url']  = $sourceUrl;
            $settings['_loaded_date'] = time();

            $result = $parser->parse($body, $settings);

            $filepath      = Scraper::$tempPath.'_parse_content_'.sha1(microtime(true).microtime().rand(0, 999999));
            $resultWrapper = new Result($filepath, $result);

            $tempPath      = null;
            $result        = null;
            $sourceUrl     = null;
            $data          = null;
            $filepath      = null;
            $body          = null;
            $response      = null;
            unset($data, $sourceUrl, $tempPath, $result, $filepath, $body, $response);

            $parseResults[] = $resultWrapper;

            gc_collect_cycles();
        }

        $responses = null;
        $storage[$this->required[0]] = null;
        $parser       = null;
        $instructions = null;

        unset($responses, $storage[$this->required[0]], $parser, $instructions);
        gc_collect_cycles();

        return $parseResults;
    }
}
