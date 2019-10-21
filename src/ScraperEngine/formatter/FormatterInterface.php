<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 01:34
 */

namespace ScraperEngine\Formatter;

/**
 * Interface FormatterInterface
 * @package ScraperEngine\Formatter
 */
interface FormatterInterface
{
    /**
     * @param mixed $input
     * @return mixed
     */
    public function format($input);
}
