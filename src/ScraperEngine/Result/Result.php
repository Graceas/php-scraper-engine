<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-23
 * Time: 16:35
 */

namespace ScraperEngine\Result;

/**
 * Class Result
 * @package ScraperEngine\Result
 */
class Result
{
    /**
     * @var string
     */
    private $filepath;

    /**
     * Result constructor.
     * @param string $filepath
     * @param mixed  $content
     */
    public function __construct($filepath, $content)
    {
        $this->filepath = $filepath;

        file_put_contents($this->filepath, serialize($content));

        $content  = null;
        $filepath = null;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return unserialize(file_get_contents($this->filepath));
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        @unlink($this->filepath);
    }
}
