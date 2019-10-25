<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 23:58
 */

namespace ScraperEngine\Rules;

/**
 * Class GetResultsRule
 * @package ScraperEngine\Rules
 */
class GetResultsRule extends BaseRule
{
    /**
     * settings:
     * parser - class implemented ParserInterface
     * @param array $storage
     * @return mixed
     */
    public function execute(&$storage)
    {
        $this->output = $storage[$this->required[0]];

        return true;
    }
}
