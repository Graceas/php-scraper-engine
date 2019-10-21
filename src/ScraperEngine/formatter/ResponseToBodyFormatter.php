<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 01:36
 */

namespace ScraperEngine\Formatter;

use ScraperEngine\Loader\Response\ResponseInterface;

/**
 * Class ResponseToBodyFormatter
 * @package ScraperEngine\Formatter
 */
class ResponseToBodyFormatter implements FormatterInterface
{
    /**
     * @param ResponseInterface $input
     * @return string
     */
    public function format($input)
    {
        return $input->getBody();
    }
}
