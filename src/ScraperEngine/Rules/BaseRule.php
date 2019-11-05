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
     * @var mixed
     */
    protected $output;

    /**
     * @param array $settings
     * @return void
     */
    public function configure($settings)
    {
        $this->settings = $settings;

        $settings = null;
        unset($settings);
    }

    /**
     * PaginatorRequestBuilderRule constructor.
     * @param string $name
     * @param array $required
     * @param array $settings
     * @param LoggerInterface|null $logger
     * @param null $output
     */
    public function __construct($name, $required, $settings, LoggerInterface $logger = null, &$output = null)
    {
        $this->name     = $name;
        $this->required = $required;
        $this->settings = $settings;
        $this->logger   = ($logger) ? $logger : new DefaultLogger();
        $this->output   = &$output;
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

    /**
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
