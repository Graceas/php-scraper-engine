<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 22:42
 */

namespace ScraperEngine\Rules;

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
     * @param array  $required
     * @param array  $settings
     */
    public function __construct($name, $required, $settings)
    {
        $this->name     = $name;
        $this->required = $required;
        $this->settings = $settings;
    }

     /**
     * @param array $storage
     * @return mixed
     */
    abstract public function execute($storage);

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
