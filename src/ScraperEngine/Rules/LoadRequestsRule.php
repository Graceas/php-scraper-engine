<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 23:58
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Loader\LoaderInterface;
use ScraperEngine\Loader\Request\RequestInterface;
use ScraperEngine\Loader\Response\ResponseInterface;

/**
 * Class LoadRequestsRule
 * @package ScraperEngine\Rules
 */
class LoadRequestsRule extends BaseRule
{
    /**
     * @var array mixed
     */
    private $responses = array();

    /**
     * @var int
     */
    private $countOfRequests = 0;

    /**
     * @var int
     */
    private $countOfProcessedRequests = 0;

    /**
     * settings:
     * loader - class implemented LoaderInterface
     * concurrency - count of parallels requests (window size)
     * @param array $storage
     * @return mixed
     * @throws \ScraperEngine\Exception\ScraperEngineException
     */
    public function execute($storage)
    {
        /** @var LoaderInterface $loader */
        $loader   = $this->settings['loader'];
        $requests = array();
        $callback = array($this, 'storeResponse');

        $this->countOfRequests = count($storage[$this->required[0]]);
        $this->logger->addDebug(sprintf('[LoadRequestsRule] Total Requests: %s', $this->countOfRequests));
        /** @var RequestInterface $request */
        $requestsForLoad = $storage[$this->required[0]];
        foreach ($requestsForLoad as $request) {
            $requests[] = $request->setCallback($callback)->getRequest();
        }

        $loader->setRequests($requests);
        $loader->execute((isset($this->settings['concurrency'])) ? $this->settings['concurrency'] : null);

        $this->logger->addDebug(sprintf('[LoadRequestsRule] Traffic In: %s', $loader->getTrafficIn()));
        $this->logger->addDebug(sprintf('[LoadRequestsRule] Traffic Out: %s', $loader->getTrafficOut()));
        $this->logger->addDebug(sprintf('[LoadRequestsRule] Count of responses: %s', count($this->responses)));

        return $this->responses;
    }

    /**
     * @param ResponseInterface $response
     */
    public function storeResponse($response)
    {
        $this->countOfProcessedRequests++;
        $this->logger->addInfo(sprintf('[LoadRequestsRule][Loaded][%s/%s][url:%s]', $this->countOfProcessedRequests, $this->countOfRequests, $response->getRequest()->getUrl()));
        $this->logger->addDebug(sprintf('[LoadRequestsRule][Loaded][%s/%s][status:%s]', $this->countOfProcessedRequests, $this->countOfRequests, print_r($response->getInfo(), true)));

        $response = new $this->settings['response_class']($response);
        $this->responses[] = $response;
    }
}
