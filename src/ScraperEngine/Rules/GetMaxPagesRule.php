<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 23:58
 */

namespace ScraperEngine\Rules;

use ScraperEngine\Scraper;

/**
 * Class GetMaxPagesRule
 * @package ScraperEngine\Rules
 */
class GetMaxPagesRule extends BaseRule
{
    /**
     * settings:
     * scraper - class implemented Scraper
     * @param array $storage
     * @return mixed
     * @throws \ScraperEngine\Exception\ScraperEngineException
     */
    public function execute(&$storage)
    {
        /** @var Scraper $scraper */
        $scraper = $this->settings['scraper'];
        $result  = array();
        $rules   = array(
            new PaginatorRequestBuilderRule(
                'max_pages',
                array(),
                array(
                    'categories'              => $this->settings['categories'],
                    'base_url'                => $this->settings['base_url'],
                    'create_request_function' => $this->settings['create_request_function']
                ),
                $scraper->getLogger()
            ),
            new LoadRequestsRule(
                'max_pages_responses',
                array(
                    'max_pages'
                ),
                array(
                    'loader'         => $this->settings['loader'],
                    'response_class' => $this->settings['response_class'],
                    'concurrency'    => $this->settings['concurrency']
                ),
                $scraper->getLogger()
            ),
            new ParseResponsesRule(
                'max_pages_results',
                array(
                    'max_pages_responses'
                ),
                array(
                    'parser'       => $this->settings['parser'],
                    'instructions' => $this->settings['instructions']
                ),
                $scraper->getLogger()
            ),
            new FormatResponsesRule(
                'max_pages_formatted',
                array(
                    'max_pages_results'
                ),
                array(
                    'formatter' => $this->settings['formatter']
                ),
                $scraper->getLogger()
            ),
            new GetResultsRule(
                'get_result',
                array(
                    'max_pages_formatted'
                ),
                array(),
                $scraper->getLogger(),
                $result
            ),
            new ClearRule(
                'clear',
                array(
                    'max_pages',
                    'max_pages_responses',
                    'max_pages_results',
                    'max_pages_formatted',
                    'get_result'
                ),
                array(),
                $scraper->getLogger()
            ),
        );

        $scraper->setRules($rules);
        $scraper->execute();

        $rules = null;

        $scraper = null;
        unset($scraper);

        return $result;
    }
}
