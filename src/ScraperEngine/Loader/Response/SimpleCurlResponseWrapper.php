<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 21:52
 */

namespace ScraperEngine\Loader\Response;

use ScraperEngine\Helper\FileHelper;
use ScraperEngine\Scraper;
use SimpleCurlWrapper\SimpleCurlResponse;

/**
 * Class SimpleCurlRequestWrapper
 * @package ScraperEngine\Loader\Request
 */
class SimpleCurlResponseWrapper implements ResponseInterface
{
    /**
     * @var string
     */
    private $responsePath;

    /**
     * @var string
     */
    private $requestedUrl = '';

    /**
     * SimpleCurlRequestWrapper constructor.
     * @param SimpleCurlResponse|null $response
     */
    public function __construct(SimpleCurlResponse &$response)
    {
        $this->requestedUrl = $response->getRequest()->getUrl();
        $responseHash       = sha1(rand(0, 999999).microtime(true).microtime(false).rand(0, 999999));
        $this->responsePath = Scraper::$tempPath.'_resp_'.$responseHash;

        FileHelper::writeFile($this->responsePath, serialize($response));

        $response           = null;
        $responseHash       = null;

        unset($response, $responseHash);
    }

    /**
     * @return string
     */
    public function &getHeaders()
    {
        return unserialize(FileHelper::readFile($this->responsePath))->getHeaders();
    }

    /**
     * @return string
     */
    public function getRequestedUrl()
    {
        return $this->requestedUrl;
    }

    /**
     * @return string
     */
    public function getResponsePath()
    {
        return $this->responsePath;
    }

    /**
     * @return array
     */
    public function getHeadersAsArray()
    {
        return unserialize(FileHelper::readFile($this->responsePath))->getHeadersAsArray();
    }

    /**
     * @return string
     */
    public function &getBody()
    {
        return unserialize(FileHelper::readFile($this->responsePath))->getBody();
    }

    /**
     * @return string
     */
    public function getBodyAsJson()
    {
        return unserialize(FileHelper::readFile($this->responsePath))->getBodyAsJson();
    }

    /**
     * @return array
     */
    public function &getInfo()
    {
        return unserialize(FileHelper::readFile($this->responsePath))->getInfo();
    }

    /**
     * @return mixed
     */
    public function &getRequest()
    {
        return unserialize(FileHelper::readFile($this->responsePath))->getRequest();
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        if (file_exists($this->responsePath)) {
            unlink($this->responsePath);
        }
    }
}
