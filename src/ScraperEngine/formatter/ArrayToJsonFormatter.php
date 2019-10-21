<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 01:36
 */

namespace ScraperEngine\Formatter;


/**
 * Class ArrayToJsonFormatter
 * @package ScraperEngine\Formatter
 */
class ArrayToJsonFormatter implements FormatterInterface
{
    const OPTION_SPLIT_ARRAY_TO_SINGLE_ELEMENTS = 'option_split_array_to_single_elements';

    /**
     * @var array
     */
    private $settings = array(
        self::OPTION_SPLIT_ARRAY_TO_SINGLE_ELEMENTS => false
    );

    /**
     * ArrayToJsonFormatter constructor.
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
        if ($this->settings[self::OPTION_SPLIT_ARRAY_TO_SINGLE_ELEMENTS]) {
            $formatted = array();
            foreach ($input as $item) {
                $formatted[] = json_encode($item);
            }

            return $formatted;
        } else {
            return json_encode($input);
        }
    }
}
