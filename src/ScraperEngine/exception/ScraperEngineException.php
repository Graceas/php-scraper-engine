<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-10
 * Time: 16:10
 */

namespace ScraperEngine\Exception;

use Throwable;

/**
 * Class ScraperEngineException
 * @package ScraperEngine\Exception
 */
class ScraperEngineException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
