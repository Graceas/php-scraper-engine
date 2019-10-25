<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-23
 * Time: 16:35
 */

namespace ScraperEngine\Result;

use ScraperEngine\Helper\FileHelper;

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
    public function __construct($filepath, &$content)
    {
        $this->filepath = $filepath;

        $serializedContent = serialize($content);
        FileHelper::writeFile($this->filepath, $serializedContent);

        $content           = null;
        $filepath          = null;
        $serializedContent = null;

        unset($content, $filepath, $serializedContent);
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return unserialize(FileHelper::readFile($this->filepath));
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        @unlink($this->filepath);
    }
}
