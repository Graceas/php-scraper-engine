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

        foreach ($this->settings['categories'] as $category => $pageLimit) {
            for ($i = $pageLimit['start_page']; $i <= $pageLimit['end_page']; $i++) {
                $url = str_replace(array('{category}', '{page}'), array($category, $i), $this->settings['base_url']);
                $requests[] = call_user_func_array($this->settings['create_request_function'], array($url));
            }
        }

        return $requests;
    }
}