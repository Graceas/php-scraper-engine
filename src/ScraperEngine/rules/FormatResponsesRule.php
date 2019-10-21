<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 01:29
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Formatter\FormatterInterface;

/**
 * Class FormatResponsesRule
 * @package ScraperEngine\Rules
 */
class FormatResponsesRule extends BaseRule
{
    /**
     * @param array $storage
     * @return mixed
     */
    public function execute($storage)
    {
        /** @var FormatterInterface $formatter */
        $formatter = $this->settings['formatter'];

        return $formatter->format($storage[$this->required[0]]);
    }
}
