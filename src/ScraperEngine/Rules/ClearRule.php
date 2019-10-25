<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 23:58
 */

namespace ScraperEngine\Rules;

/**
 * Class ClearRule
 * @package ScraperEngine\Rules
 */
class ClearRule extends BaseRule
{
    /**
     * settings:
     * parser - class implemented ParserInterface
     * @param array $storage
     * @return mixed
     */
    public function execute(&$storage)
    {
        $storage = null;
        unset($storage);

        return true;
    }
}
