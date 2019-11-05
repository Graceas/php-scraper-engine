<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 22:47
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Helper\StringHelper;
use ScraperEngine\Loader\Request\RequestInterface;
use ScraperEngine\Result\Result;

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
        $parameters = (isset($this->settings['parameters'])) ? $this->settings['parameters'] : (isset($this->required[0])) ? $storage[$this->required[0]] : array(1);

        foreach ($parameters as $parameter) {
            if (isset($this->required[1]) && isset($storage[$this->required[1]])) {
                $value = $storage[$this->required[1]];

                // TODO: rewrite for multiple parameters
                if ($value[0] instanceof Result) {
                    $value = $value[0]->get();
                }

                $parameter = array_merge($parameter, $value);

                $value = null;
                unset($value);
            }

            if (!$parameter) {
                $parameter = array();
            }

            /** @var RequestInterface $request */
            $request = new $this->settings['request_class'];

            $request->setUrl(StringHelper::replaceValues($this->settings['request']['url'], $parameter, $this->settings['variables']));

            $headers = array();
            foreach ($this->settings['request']['headers'] as $header) {
                $headers[] = StringHelper::replaceValues($header, $parameter, $this->settings['variables']);
            }

            $request->setHeaders($headers);
            $headers = null;
            unset($headers);

            if (is_array($this->settings['request']['data'])) {
                $data = array();
                foreach ($this->settings['request']['data'] as $key => $value) {
                    $data[StringHelper::replaceValues($key, $parameter, $this->settings['variables'])] = StringHelper::replaceValues($value, $parameter, $this->settings['variables']);
                }
                $request->setData($data);
                $data = null;

                unset($data);
            } else {
                $request->setData(StringHelper::replaceValues($this->settings['request']['data'], $parameter, $this->settings['variables']));
            }

            $request->setMethod($this->settings['request']['method']);

            $options = array();
            foreach ($this->settings['request']['options'] as $key => $value) {
                $options[StringHelper::replaceValues($key, $parameter, $this->settings['variables'])] = StringHelper::replaceValues($value, $parameter, $this->settings['variables']);
            }
            $request->setOptions($options);

            $options = null;
            unset($options);

            $requests[] = $request;
        }

        $requests = array_filter($requests);

        return $requests;
    }
}
