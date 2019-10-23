<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 21:47
 */

namespace ScraperEngine\Loader\Request;

/**
 * Interface RequestInterface
 * @package ScraperEngine\Loader
 */
interface RequestInterface
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @return bool|string
     */
    public function getUrl();

    /**
     * @param bool|string $url
     * @return RequestInterface
     */
    public function setUrl($url);

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @param string $method
     * @return RequestInterface
     */
    public function setMethod($method);

    /**
     * @return array|null
     */
    public function getData();

    /**
     * @param array|null $data
     * @return RequestInterface
     */
    public function setData($data);

    /**
     * @return array|null
     */
    public function getHeaders();

    /**
     * @param array|null $headers
     * @return RequestInterface
     */
    public function setHeaders($headers);

    /**
     * @return array|null
     */
    public function getOptions();

    /**
     * @param array|null $options
     * @return RequestInterface
     */
    public function setOptions($options);

    /**
     * @return callable|null
     */
    public function getCallback();

    /**
     * @param callable|null $callback
     * @return RequestInterface
     */
    public function setCallback($callback);

    /**
     * @return mixed
     */
    public function getRequest();
}
