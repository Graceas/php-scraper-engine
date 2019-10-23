<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 22:42
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Logger\DefaultLogger;
use ScraperEngine\Logger\LoggerInterface;

/**
 * Class BuildRequestsRule
 * @package ScraperEngine\Rules
 */
abstract class BaseRule implements RuleInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var array
     */
    protected $required = array();

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param $settings
     * @return void
     */
    public function configure($settings)
    {
        $this->settings = $settings;
    }

    /**
     * PaginatorRequestBuilderRule constructor.
     * @param string $name
     * @param array $required
     * @param array $settings
     * @param LoggerInterface|null $logger
     */
    public function __construct($name, $required, $settings, LoggerInterface $logger = null)
    {
        $this->name     = $name;
        $this->required = $required;
        $this->settings = $settings;
        $this->logger   = ($logger) ? $logger : new DefaultLogger();
    }

     /**
     * @param array $storage
     * @return mixed
     */
    abstract public function execute(&$storage);

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRequired()
    {
        return $this->required;
    }
}
