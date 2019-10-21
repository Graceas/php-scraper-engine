<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 02:13
 */

namespace ScraperEngine\Storage;

/**
 * Class FileStorage
 * @package ScraperEngine\Storage
 */
class FileStorage implements StorageInterface
{
    const OPTION_FILE_FLAG = 'option_file_flag';

    /**
     * @var array
     */
    private $settings = array(
        self::OPTION_FILE_FLAG => FILE_APPEND
    );

    /**
     * @var string
     */
    private $path;

    /**
     * FileStorage constructor.
     * @param string $path
     * @param array  $settings
     */
    public function __construct($path, $settings = array())
    {
        $this->path     = $path;
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return file_put_contents($this->path, $data.PHP_EOL, $this->settings[self::OPTION_FILE_FLAG]);
    }
}
