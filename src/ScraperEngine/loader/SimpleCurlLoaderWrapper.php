<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 22:12
 */

namespace ScraperEngine\Loader;

use ScraperEngine\Exception\ScraperEngineException;
use ScraperEngine\Loader\Request\RequestInterface;
use ScraperEngine\Loader\Response\ResponseInterface;
use ScraperEngine\Loader\Response\SimpleCurlResponseWrapper;
use SimpleCurlWrapper\SimpleCurlWrapper;

/**
 * Class SimpleCurlLoaderWrapper
 * @package ScraperEngine\Loader
 */
class SimpleCurlLoaderWrapper implements LoaderInterface
{
    /**
     * @var SimpleCurlWrapper
     */
    private $loader;

    /**
     * SimpleCurlLoaderWrapper constructor.
     * @param SimpleCurlWrapper|null $loader
     * @throws ScraperEngineException
     */
    public function __construct(SimpleCurlWrapper $loader = null)
    {
        try {
            $this->loader = ($loader) ? $loader : new SimpleCurlWrapper();
        } catch (\Exception $e) {
            throw new ScraperEngineException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Execute multiple requests
     *
     * @param int|null $windowSize Window size is the max number of simultaneous connections allowed (if null use default).
     *
     * @throws ScraperEngineException
     */
    public function execute($windowSize = null)
    {
        try {
            $this->loader->execute($windowSize);
        } catch (\Exception $e) {
            throw new ScraperEngineException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Execute single Request
     *
     * @param RequestInterface $request Request
     *
     * @return ResponseInterface
     */
    public function executeRequest(RequestInterface $request)
    {
        return new SimpleCurlResponseWrapper($this->loader->executeRequest($request->getRequest()));
    }

    /**
     * Remove single request
     *
     * @param int $index
     *
     * @return boolean
     */
    public function removeRequest($index)
    {
        return $this->loader->removeRequest($index);
    }

    /**
     * @return RequestInterface[]
     */
    public function &getRequests()
    {
        $requests = $this->loader->getRequests();
        $wrappedRequests = array();

        foreach ($requests as $request) {
            $wrappedRequests[] = $request;
        }

        return $wrappedRequests;
    }

    /**
     * @return int
     */
    public function getTrafficIn()
    {
        return $this->loader->getTrafficIn();
    }

    /**
     * @return int
     */
    public function getTrafficOut()
    {
        return $this->loader->getTrafficOut();
    }

    /**
     * @return LoaderInterface
     */
    public function resetTrafficIn()
    {
        $this->loader->resetTrafficIn();

        return $this;
    }

    /**
     * @return LoaderInterface
     */
    public function resetTrafficOut()
    {
        $this->loader->resetTrafficOut();

        return $this;
    }

    /**
     * @param bool $immediatelyStop
     * @return LoaderInterface
     */
    public function setImmediatelyStop($immediatelyStop)
    {
        $this->loader->setImmediatelyStop($immediatelyStop);

        return $this;
    }

    /**
     * @param bool $riseErrors
     * @return LoaderInterface
     */
    public function setRiseErrors($riseErrors)
    {
        $this->loader->setRiseErrors($riseErrors);

        return $this;
    }

    /**
     * @param float $timeout
     * @return LoaderInterface
     */
    public function setTimeout($timeout)
    {
        $this->loader->setTimeout($timeout);

        return $this;
    }

    /**
     * @param array $options
     * @return LoaderInterface
     */
    public function setOptions($options)
    {
        $this->loader->setOptions($options);

        return $this;
    }

    /**
     * @param $windowSize
     * @return LoaderInterface
     */
    public function setWindowSize($windowSize)
    {
        $this->loader->setWindowSize($windowSize);

        return $this;
    }

    /**
     * @param array $requests
     * @return LoaderInterface
     */
    public function setRequests($requests)
    {
        $this->loader->setRequests($requests);

        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return LoaderInterface
     */
    public function addRequest(RequestInterface $request)
    {
        $this->loader->addRequest($request->getRequest());

        return $this;
    }
}
