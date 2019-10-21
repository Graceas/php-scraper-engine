<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 23:58
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Loader\LoaderInterface;
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
     * create_request_function - create request function
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
        foreach ($storage[$this->required[0]] as $url) {
            $requests[] = call_user_func_array($this->settings['create_request_function'], array($url, $callback))->getRequest();
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
