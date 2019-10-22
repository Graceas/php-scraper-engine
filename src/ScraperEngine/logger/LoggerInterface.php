<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-22
 * Time: 14:13
 */

namespace ScraperEngine\Logger;

/**
 * Interface LoggerInterface
 * @package ScraperEngine\Logger
 */
interface LoggerInterface
{
    /**
     * @param string $text
     */
    public function addWarning($text);

    /**
     * @param string $text
     */
    public function addCritical($text);

    /**
     * @param string $text
     */
    public function addInfo($text);

    /**
     * @param string $text
     */
    public function addDebug($text);
}
