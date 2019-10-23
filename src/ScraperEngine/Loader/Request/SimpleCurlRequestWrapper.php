<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 21:52
 */

namespace ScraperEngine\Loader\Request;

use SimpleCurlWrapper\SimpleCurlRequest;

/**
 * Class SimpleCurlRequestWrapper
 * @package ScraperEngine\Loader\Request
 */
class SimpleCurlRequestWrapper implements RequestInterface
{
    /**
     * @var SimpleCurlRequest
     */
    private $request;

    /**
     * SimpleCurlRequestWrapper constructor.
     * @param SimpleCurlRequest|null $request
     */
    public function __construct(SimpleCurlRequest $request = null)
    {
        $this->request = ($request) ? $request : new SimpleCurlRequest();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->request->toArray();
    }

    /**
     * @return bool|string
     */
    public function getUrl()
    {
        return $this->request->getUrl();
    }

    /**
     * @param bool|string $url
     * @return RequestInterface
     */
    public function setUrl($url)
    {
        $this->request->setUrl($url);

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * @param string $method
     * @return RequestInterface
     */
    public function setMethod($method)
    {
        $this->request->setMethod($method);

        return $this;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        return $this->request->getData();
    }

    /**
     * @param array|null $data
     * @return RequestInterface
     */
    public function setData($data)
    {
        $this->request->setData($data);

        return $this;
    }

    /**
     * @return array|null
     */
    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    /**
     * @param array|null $headers
     * @return RequestInterface
     */
    public function setHeaders($headers)
    {
        $this->request->setHeaders($headers);

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOptions()
    {
        return $this->request->getOptions();
    }

    /**
     * @param array|null $options
     * @return RequestInterface
     */
    public function setOptions($options)
    {
        $this->request->setOptions($options);

        return $this;
    }

    /**
     * @return callable|null
     */
    public function getCallback()
    {
        return $this->request->getCallback();
    }

    /**
     * @param callable|null $callback
     * @return RequestInterface
     */
    public function setCallback($callback)
    {
        $this->request->setCallback($callback);

        return $this;
    }

    /**
     * @return SimpleCurlRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
}
