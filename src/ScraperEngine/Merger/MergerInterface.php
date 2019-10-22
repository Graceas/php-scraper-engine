<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-22
 * Time: 19:45
 */

namespace ScraperEngine\Merger;

/**
 * Interface MergerInterface
 * @package ScraperEngine\Merger
 */
interface MergerInterface
{
    /**
     * @param mixed $input1
     * @param mixed $input2
     * @return mixed
     */
    public function merge($input1, $input2);
}
