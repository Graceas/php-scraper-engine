<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 21:46
 */

namespace ScraperEngine\Loader;

use ScraperEngine\Exception\ScraperEngineException;
use ScraperEngine\Loader\Request\RequestInterface;

/**
 * Interface LoaderInterface
 * @package ScraperEngine\Loader
 */
interface LoaderInterface
{
    /**
     * Execute multiple requests
     *
     * @param int|null $windowSize Window size is the max number of simultaneous connections allowed (if null use default).
     *
     * @return string|bool
     *
     * @throws ScraperEngineException
     */
    public function execute($windowSize = null);

    /**
     * Execute single Request
     *
     * @param RequestInterface $request  Request
     *
     * @return string
     */
    public function executeRequest(RequestInterface $request);

    /**
     * Remove single request
     *
     * @param int $index
     *
     * @return boolean
     */
    public function removeRequest($index);

    /**
     * @return RequestInterface[]
     */
    public function getRequests();

    /**
     * @return int
     */
    public function getTrafficIn();

    /**
     * @return int
     */
    public function getTrafficOut();

    /**
     * @return LoaderInterface
     */
    public function resetTrafficIn();

    /**
     * @return LoaderInterface
     */
    public function resetTrafficOut();

    /**
     * @param bool $immediatelyStop
     * @return LoaderInterface
     */
    public function setImmediatelyStop($immediatelyStop);

    /**
     * @param bool $riseErrors
     * @return LoaderInterface
     */
    public function setRiseErrors($riseErrors);

    /**
     * @param float $timeout
     * @return LoaderInterface
     */
    public function setTimeout($timeout);

    /**
     * @param array $options
     * @return LoaderInterface
     */
    public function setOptions($options);

    /**
     * @param $windowSize
     * @return LoaderInterface
     */
    public function setWindowSize($windowSize);

    /**
     * @param array $requests
     * @return LoaderInterface
     */
    public function setRequests($requests);

    /**
     * @param RequestInterface $request
     * @return LoaderInterface
     */
    public function addRequest(RequestInterface $request);
}
