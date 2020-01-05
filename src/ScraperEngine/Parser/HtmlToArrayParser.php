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
    public function __construct()
    {
        $this->parser = new HtmlParser();
    }

    /**
     * @param string $content
     * @param array  $settings [instructions => '']
     * @return array
     * @throws ScraperEngineException
     */
    public function &parse(&$content, $settings = array())
    {
        try {
            $values = $this->parser->getValues($content, (is_array($settings['instructions'])) ? implode(PHP_EOL, $settings['instructions']) : $settings['instructions']);
            $content  = null;
            $settings = null;

            return $values;
        } catch (\Exception $e) {
            $content  = null;
            $settings = null;

            throw new ScraperEngineException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
