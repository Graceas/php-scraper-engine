<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 02:12
 */

namespace ScraperEngine\Storage;

/**
 * Interface StorageInterface
 * @package ScraperEngine\Storage
 */
interface StorageInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data);
}
