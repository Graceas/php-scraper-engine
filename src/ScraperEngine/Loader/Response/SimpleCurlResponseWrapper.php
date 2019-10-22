<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 21:52
 */

namespace ScraperEngine\Loader\Response;

use ScraperEngine\Loader\Request\RequestInterface;
use ScraperEngine\Loader\Request\SimpleCurlRequestWrapper;
use SimpleCurlWrapper\SimpleCurlRequest;
use SimpleCurlWrapper\SimpleCurlResponse;

/**
 * Class SimpleCurlRequestWrapper
 * @package ScraperEngine\Loader\Request
 */
class SimpleCurlResponseWrapper implements ResponseInterface
{
    /**
     * @var SimpleCurlResponse
     */
    private $response;

    /**
     * SimpleCurlRequestWrapper constructor.
     * @param SimpleCurlResponse|null $response
     */
    public function __construct(SimpleCurlResponse $response = null)
    {
        $this->response = ($response) ? $response : new SimpleCurlResponse("", "", array(), new SimpleCurlRequest());
    }

    /**
     * @return string
     */
    public function &getHeaders()
    {
        return $this->response->getHeaders();
    }

    /**
     * @return array
     */
    public function getHeadersAsArray()
    {
        return $this->response->getHeadersAsArray();
    }

    /**
     * @return string
     */
    public function &getBody()
    {
        return $this->response->getBody();
    }

    /**
     * @return string
     */
    public function &getBodyAsJson()
    {
        return $this->response->getBodyAsJson();
    }

    /**
     * @return array
     */
    public function &getInfo()
    {
        return $this->response->getInfo();
    }

    /**
     * @return RequestInterface
     */
    public function &getRequest()
    {
        $request = new SimpleCurlRequestWrapper($this->response->getRequest());

        return $request;
    }
}
