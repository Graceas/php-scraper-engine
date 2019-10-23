<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 00:33
 */

namespace ScraperEngine\Parser;

use HtmlParser\HtmlParser;
use ScraperEngine\Exception\ScraperEngineException;
use ScraperEngine\Loader\Response\ResponseInterface;

/**
 * Class HtmlToJsonParser
 * @package ScraperEngine\Parser
 */
class HtmlToArrayParser implements ParserInterface
{
    /**
     * @var HtmlParser
     */
    private $parser;

    /**
     * HtmlToJsonParser constructor.
     * @param HtmlParser|null $parser
     */
    public function __construct(HtmlParser $parser = null)
    {
        $this->parser = ($parser) ? $parser : new HtmlParser();
    }

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
            $response = $content;
            $sourceUrl = $response->getInfo()['requested_url'];
            $content   = $response->getBody();
            $response  = null;
        }

        try {
            $values = $this->parser->getValues($content, $settings['instructions']);
            $content  = null;
            $settings = null;

            return array_merge(
                $values,
                array(
                    '_source_url'  => $sourceUrl,
                    '_loaded_date' => time()
                )
            );
        } catch (\Exception $e) {
            $content  = null;
            $settings = null;

            throw new ScraperEngineException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
