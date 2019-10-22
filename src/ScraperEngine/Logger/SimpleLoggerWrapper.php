<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-22
 * Time: 14:15
 */

namespace ScraperEngine\Logger;

use SimpleLogger\SimpleLogger;

/**
 * Class SimpleLoggerWrapper
 * @package ScraperEngine\Logger
 */
class SimpleLoggerWrapper implements LoggerInterface
{
    /**
     * @var SimpleLogger
     */
    private $logger;

    /**
     * SimpleLoggerWrapper constructor.
     * @param SimpleLogger|null $logger
     */
    public function __construct(SimpleLogger $logger = null)
    {
        $this->logger = ($logger) ? $logger : new SimpleLogger();
    }

    /**
     * @param string $text
     */
    public function addWarning($text)
    {
        $this->logger->addWarning($text);
    }

    /**
     * @param string $text
     */
    public function addCritical($text)
    {
        $this->logger->addCritical($text);
    }

    /**
     * @param string $text
     */
    public function addInfo($text)
    {
        $this->logger->addInfo($text);
    }

    /**
     * @param string $text
     */
    public function addDebug($text)
    {
        $this->logger->addDebug($text);
    }
}
