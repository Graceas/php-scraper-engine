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
     * settings:
     * loader - class implemented LoaderInterface
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
        /** @var RequestInterface $request */
        foreach ($storage[$this->required[0]] as $request) {
            $requests[] = $request->setCallback($callback)->getRequest();
        }

        $loader->setRequests($requests);
        $loader->execute();

        return $this->responses;
    }

    /**
     * @param ResponseInterface $response
     */
    public function storeResponse($response)
    {
        $response = new $this->settings['response_class']($response);
        $this->responses[] = $response;
    }
}
