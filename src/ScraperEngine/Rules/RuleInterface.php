<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 22:40
 */

namespace ScraperEngine\Rules;

/**
 * Interface RuleInterface
 * @package ScraperEngine\Rules
 */
interface RuleInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getRequired();

    /**
     * @param $settings
     * @return void
     */
    public function configure($settings);

    /**
     * @param array $storage
     * @return mixed
     */
    public function execute($storage);
}
