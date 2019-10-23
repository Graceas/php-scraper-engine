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
    public function execute(&$storage)
    {
        /** @var FormatterInterface $formatter */
        $formatter = $this->settings['formatter'];

//        $this->logger->addDebug(sprintf('[FormatResponsesRule] Format: %s', print_r($storage[$this->required[0]], true)));
        $result = $formatter->format($storage[$this->required[0]]);
//        $this->logger->addDebug(sprintf('[FormatResponsesRule] Format result: %s', print_r($result, true)));

        $storage[$this->required[0]] = null;
        $formatter = null;

        return $result;
    }
}
