<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 22:47
 */

namespace ScraperEngine\Rules;

/**
 * Class PaginatorRequestBuilderRule
 * @package ScraperEngine\Rules
 */
class PaginatorRequestBuilderRule extends BaseRule
{
    /**
     * settings:
     * categories - array of categories ['category1' => ['start_page' => 1, 'end_page' => 10]]
     * base_url - base url http://example.com/{category}/?page={page}
     * @param array $storage
     * @return mixed
     */
    public function execute($storage)
    {
        $requests = array();
        $categories = (isset($this->settings['categories'])) ? $this->settings['categories'] : $storage[$this->required[0]];

        foreach ($categories as $category => $pageLimit) {
            for ($i = $pageLimit['start_page']; $i <= $pageLimit['end_page']; $i++) {
                $url = str_replace(array('{category}', '{page}'), array($category, $i), $this->settings['base_url']);

                $request = call_user_func_array($this->settings['create_request_function'], array($url));
                if (is_array($request)) {
                    $requests = array_merge($requests, $request);
                } else {
                    $requests[] = $request;
                }
            }
        }

        $requests = array_filter($requests);

        return $requests;
    }
}
