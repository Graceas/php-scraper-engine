<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 00:29
 */

namespace ScraperEngine\Parser;

/**
 * Interface ParserInterface
 * @package ScraperEngine\Parser
 */
interface ParserInterface
{
    /**
     * @param mixed $content
     * @param array $settings
     * @return mixed
     */
    public function &parse(&$content, $settings = array());
}
