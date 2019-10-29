<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 01:36
 */

namespace ScraperEngine\Formatter;


/**
 * Class UnpackFormatter
 * @package ScraperEngine\Formatter
 */
class UnpackFormatter implements FormatterInterface
{
    const UNPACK_KEY = 'unpack_key';

    /**
     * @var array
     */
    private $settings = array();

    /**
     * CustomFormatter constructor.
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * @param array $input
     * @return array
     */
    public function format($input)
    {
        if (!isset($this->settings[self::UNPACK_KEY])) {
            return null;
        }

        $products = array();
        /** @var \ScraperEngine\Result\Result $resource */
        foreach ($input as &$resource) {
            $items = $resource->get();
            foreach ($items[$this->settings[self::UNPACK_KEY]] as &$item) {

                $products[] = $item;

                $item   = null;
            }

            $items    = null;
            $resource = null;
        }

        $input = null;

        return $products;
    }
}
