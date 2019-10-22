<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 01:36
 */

namespace ScraperEngine\Formatter;


/**
 * Class CustomFormatter
 * @package ScraperEngine\Formatter
 */
class CustomFormatter implements FormatterInterface
{
    const OPTION_CALLBACK = 'option_callback';

    /**
     * @var array
     */
    private $settings = array(
        self::OPTION_CALLBACK => false
    );

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
        if (!isset($this->settings[self::OPTION_CALLBACK])) {
            return $input;
        }

        return call_user_func_array($this->settings[self::OPTION_CALLBACK], array($input));
    }
}
