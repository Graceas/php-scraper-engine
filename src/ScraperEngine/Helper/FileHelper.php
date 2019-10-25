<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-24
 * Time: 18:25
 */

namespace ScraperEngine\Helper;

/**
 * Class FileHelper
 * @package ScraperEngine\Helper
 */
class FileHelper
{
    /**
     * @param string $path
     * @param string $content
     */
    public static function writeFile($path, $content)
    {
        $file = fopen($path, 'w+');
        fwrite($file, $content);
        fclose($file);

        $path    = null;
        $file    = null;
        $content = null;

        unset($path, $file, $content);
    }

    /**
     * @param string $path
     * @return bool|string
     */
    public static function &readFile($path)
    {
        $file = fopen($path, 'r');
        $content = fread($file, filesize($path));
        fclose($file);

        $file = null;
        $path = null;

        unset($file, $path);

        return $content;
    }

    /**
     * @param string $path
     * @param string $content
     */
    public static function appendFile($path, $content)
    {
        $file = fopen($path, 'a+');
        fwrite($file, $content);
        fclose($file);

        $path    = null;
        $file    = null;
        $content = null;

        unset($path, $file, $content);
    }
}
