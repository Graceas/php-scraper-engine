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
 * Class LoadAndParsePagesRule
 * @package ScraperEngine\Rules
 */
class LoadAndParsePagesRule extends BaseRule
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
        $storage = array(
            'requests_to_load' => $storage[$this->getRequired()[0]]
        );
        $scraper->setStorage($storage);

        $result  = array();
        $rules   = array(
            new LoadRequestsRule(
                'index_pages_responses',
                array(
                    'requests_to_load'
                ),
                array(
                    'loader'         => $this->settings['loader'],
                    'response_class' => $this->settings['response_class'],
                    'concurrency'    => $this->settings['concurrency']
                ),
                $scraper->getLogger()
            ),
            new ParseResponsesRule(
                'index_pages_products',
                array(
                    'index_pages_responses'
                ),
                array(
                    'parser'       => $this->settings['parser'],
                    'instructions' => $this->settings['instructions']
                ),
                $scraper->getLogger()
            ),
            new FormatResponsesRule(
                'index_pages_products_formatted',
                array(
                    'index_pages_products'
                ),
                array(
                    'formatter' => $this->settings['formatter']
                ),
                $scraper->getLogger()
            ),
            new GetResultsRule(
                'get_result',
                array(
                    'index_pages_products_formatted'
                ),
                array(),
                $scraper->getLogger(),
                $result
            ),
            new ClearRule(
                'clear',
                array(
                    'index_pages_products',
                    'index_pages_responses',
                    'requests_to_load',
                ),
                array()
            ),
        );

        $scraper->setRules($rules);
        $scraper->execute();

        $rules = null;
        $scraper = null;
        $storage = null;

        unset($storage, $scraper);

        return $result;
    }
}
