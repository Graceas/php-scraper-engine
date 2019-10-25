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

            $this->logger->addWarning('mem: before load');

            $this->logger->addWarning('mem: before get response path');
            $responsePath = $response->getResponsePath();
            $this->logger->addWarning('mem: after get response path');

            $this->logger->addWarning('mem: before open file');
            $filesize     = filesize($responsePath);
            $file         = fopen($responsePath, 'r');
            $fileContent  = fread($file, $filesize);

            fclose($file);

            $this->logger->addWarning('mem: after open file');

            $this->logger->addWarning('mem: before unserialize');
            $originalResponse = unserialize($fileContent);
            $this->logger->addWarning('mem: after unserialize');

            $this->logger->addWarning('mem: before get body');
            $body             = $originalResponse->getBody();
            $this->logger->addWarning('mem: after get body');

            $this->logger->addWarning('mem: after load');

            $filesize = null;
            $file = null;
            $responsePath = null;
            $originalResponse = null;
            $fileContent = null;

            unset($filesize, $file, $responsePath, $originalResponse, $fileContent);

            $result = $parser->parse($body, $settings);

            $result['_source_url']  = $sourceUrl;
            $result['_loaded_date'] = time();

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

            $this->logger->addWarning('mem: after clear');

            gc_collect_cycles();
            $this->logger->addWarning('mem: after clear: gc');
        }

        $responses = null;
        $storage[$this->required[0]] = null;
        $parser       = null;
        $instructions = null;

        unset($responses, $storage[$this->required[0]], $parser, $instructions);
        $this->logger->addInfo('>>>>test1');
        gc_collect_cycles();
        $this->logger->addInfo('>>>>test2');

        return $parseResults;
    }
}
