<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-22
 * Time: 14:15
 */

namespace ScraperEngine\Logger;

/**
 * Class DefaultLogger
 * @package ScraperEngine\Logger
 */
class DefaultLogger implements LoggerInterface
{
    /**
     * @param string $text
     */
    public function addWarning($text)
    {
    }

    /**
     * @param string $text
     */
    public function addCritical($text)
    {
    }

    /**
     * @param string $text
     */
    public function addInfo($text)
    {
    }

    /**
     * @param string $text
     */
    public function addDebug($text)
    {
    }
}
