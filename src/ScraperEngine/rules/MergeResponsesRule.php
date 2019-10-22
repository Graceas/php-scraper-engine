<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 01:29
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Merger\MergerInterface;

/**
 * Class MergeResponsesRule
 * @package ScraperEngine\Rules
 */
class MergeResponsesRule extends BaseRule
{
    /**
     * @param array $storage
     * @return mixed
     */
    public function execute($storage)
    {
        /** @var MergerInterface $merger */
        $merger = $this->settings['merger'];

        $this->logger->addDebug(sprintf('[MergeResponsesRule] Merge: %s and %s', print_r($storage[$this->required[0]], true), print_r($storage[$this->required[1]], true)));
        $result = $merger->merge($storage[$this->required[0]], $storage[$this->required[1]]);
        $this->logger->addDebug(sprintf('[MergeResponsesRule] Merge result: %s', print_r($result, true)));

        return $result;
    }
}
