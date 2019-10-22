<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-22
 * Time: 19:46
 */

namespace ScraperEngine\Merger;

/**
 * Class CustomMerger
 * @package ScraperEngine\Merger
 */
class CustomMerger implements MergerInterface
{
    const OPTION_CALLBACK = 'option_callback';

    /**
     * @var array
     */
    private $settings = array(
        self::OPTION_CALLBACK => false
    );

    /**
     * CustomMerger constructor.
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        $this->settings = array_merge($this->settings, $settings);
    }

    /**
     * @param mixed $input1
     * @param mixed $input2
     * @return mixed
     */
    public function merge($input1, $input2)
    {
        if (!isset($this->settings[self::OPTION_CALLBACK])) {
            return null;
        }

        return call_user_func_array($this->settings[self::OPTION_CALLBACK], array($input1, $input2));
    }
}
