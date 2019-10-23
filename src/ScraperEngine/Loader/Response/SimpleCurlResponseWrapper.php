<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 21:52
 */

namespace ScraperEngine\Loader\Response;

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
     * SimpleCurlRequestWrapper constructor.
     * @param SimpleCurlResponse|null $response
     */
    public function __construct(SimpleCurlResponse &$response)
    {
        $pid = getmypid();
        $loaderTempDir = sys_get_temp_dir().'/_loader_/';
        $tempPath = $loaderTempDir.$pid.'/';

        if (!file_exists($loaderTempDir)) {
            mkdir($loaderTempDir);
        }

        if (!file_exists($tempPath)) {
            mkdir($tempPath);
        }

        $responseSerialized = serialize($response);
        $responseHash       = sha1($responseSerialized.microtime(true).microtime(false).rand(0, 999999));
        $this->responsePath = $tempPath.'_resp_'.$responseHash;
        file_put_contents($this->responsePath, $responseSerialized);
    }

    /**
     * @return string
     */
    public function &getHeaders()
    {
        return unserialize(file_get_contents($this->responsePath))->getHeaders();
    }

    /**
     * @return array
     */
    public function getHeadersAsArray()
    {
        return unserialize(file_get_contents($this->responsePath))->getHeadersAsArray();
    }

    /**
     * @return string
     */
    public function &getBody()
    {
        return unserialize(file_get_contents($this->responsePath))->getBody();
    }

    /**
     * @return string
     */
    public function getBodyAsJson()
    {
        return unserialize(file_get_contents($this->responsePath))->getBodyAsJson();
    }

    /**
     * @return array
     */
    public function &getInfo()
    {
        return unserialize(file_get_contents($this->responsePath))->getInfo();
    }

    /**
     * @return mixed
     */
    public function &getRequest()
    {
        return unserialize(file_get_contents($this->responsePath))->getRequest();
    }

    public function __destruct()
    {
        @unlink($this->responsePath);
    }
}
