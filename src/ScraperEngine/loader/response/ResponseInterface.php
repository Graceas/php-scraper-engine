<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 22:01
 */

namespace ScraperEngine\Loader\Response;

use ScraperEngine\Loader\Request\RequestInterface;

/**
 * Interface ResponseInterface
 */
interface ResponseInterface
{
    /**
     * @return string
     */
    public function &getHeaders();

    /**
     * @return array
     */
    public function getHeadersAsArray();

    /**
     * @return string
     */
    public function &getBody();

    /**
     * @return string
     */
    public function &getBodyAsJson();

    /**
     * @return array
     */
    public function &getInfo();

    /**
     * @return RequestInterface
     */
    public function &getRequest();
}
