<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 22:47
 */

namespace ScraperEngine\Rules;

/**
 * Class RequestsBuilderRule
 * @package ScraperEngine\Rules
 */
class RequestsBuilderRule extends BaseRule
{
    /**
     * settings:
     * parameters - array of parameters for request
     * @param array $storage
     * @return mixed
     */
    public function execute(&$storage)
    {
        $requests = array();
        $parameters = (isset($this->settings['parameters'])) ? $this->settings['parameters'] : $storage[$this->required[0]];

        foreach ($parameters as $parameter) {
            if (isset($this->required[1])) {
                $parameter = array_merge($parameter, $storage[$this->required[1]]);
            }

            $request = call_user_func_array($this->settings['create_request_function'], array($parameter));
            if (is_array($request)) {
                $requests = array_merge($requests, $request);
            } else {
                $requests[] = $request;
            }
        }

        $requests = array_filter($requests);

        return $requests;
    }
}
