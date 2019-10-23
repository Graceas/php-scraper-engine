<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 02:11
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Storage\StorageInterface;

/**
 * Class StoreDataRule
 * @package ScraperEngine\Rules
 */
class StoreDataRule extends BaseRule
{
    /**
     * @param array $storage
     * @return mixed
     */
    public function execute(&$storage)
    {
        /** @var StorageInterface $storageClass */
        $storageClass = $this->settings['storage'];
        $data = $storage[$this->required[0]];
        foreach ($data as $item) {
            $storageClass->store($item);
        }

        return true;
    }
}
