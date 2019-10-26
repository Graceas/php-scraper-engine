ScraperEngine
=============

Scraper engine.

Installation
============

Through composer:

    "require": {
        ...
        "graceas/php-scraper-engine": "v0.2.1"
        ...
    }

Usage
=====

    include_once __DIR__.'/../vendor/autoload.php';
    
    
    $scraper = new \ScraperEngine\Scraper(array(
        // prepare URLs
        new \ScraperEngine\Rules\PaginatorRequestBuilderRule(
            'index_pages', // store as
            array(), // usage from store
            array( // settings
                'categories' => array(
                    'cat1/subcat1' => array(
                        'start_page' => 3,
                        'end_page'   => 5
                    ),
                    'cat1/subcat2' => array(
                        'start_page' => 3,
                        'end_page'   => 5
                    ),
                ),
                'base_url' => 'https://www.example.com/{category}/?page={page}',
                'create_request_function' => function ($url) {
                 return (new \ScraperEngine\Loader\Request\SimpleCurlRequestWrapper())
                     ->setUrl($url)
                     ->setMethod(SimpleCurlWrapper\SimpleCurlRequest::METHOD_GET)
                     ->setHeaders(array(
                         'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
                         'accept-language: en-GB,en-US;q=0.9,en;q=0.8,ru;q=0.7',
                         'cache-control: no-cache',
                         'cookie: dfp_segment_test=47; dfp_segment_test_v3=17; dfp_segment_test_v4=62; dfp_segment_test_oa=2',
                         'pragma: no-cache',
                         'referer: https://www.example.com/',
                         'sec-fetch-mode: navigate',
                         'sec-fetch-site: same-origin',
                         'sec-fetch-user: ?1',
                         'upgrade-insecure-requests: 1',
                         'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36',
                     ));
                }
            )
        ),
        // create requests and load
        new \ScraperEngine\Rules\LoadRequestsRule(
            'index_pages_responses', // store as
            array( // usage from store
                'index_pages'
            ),
            array( // settings
                'loader' => new \ScraperEngine\Loader\SimpleCurlLoaderWrapper(),
                'response_class' => '\ScraperEngine\Loader\Response\SimpleCurlResponseWrapper'
            )
        ),
        // parse responses content
        new \ScraperEngine\Rules\ParseResponsesRule(
            'index_pages_products',
            array(
                'index_pages_responses'
            ),
            array(
                'parser'       => new \ScraperEngine\Parser\HtmlToArrayParser(),
                'instructions' => file_get_contents('index_pages.xpath')
            )
        ),
        // format multi-dimesion array to single-dimension
        new \ScraperEngine\Rules\FormatResponsesRule(
            'index_pages_products_formatted_flatted',
            array(
                'index_pages_products'
            ),
            array(
                'formatter' => new \ScraperEngine\Formatter\ArrayToFlatArrayFormatter()
            )
        ),
        // format every array item to json
        new \ScraperEngine\Rules\FormatResponsesRule(
            'index_pages_products_formatted_json',
            array(
                'index_pages_products_formatted_flatted'
            ),
            array(
                'formatter' => new \ScraperEngine\Formatter\ArrayToJsonFormatter(array(
                    \ScraperEngine\Formatter\ArrayToJsonFormatter::OPTION_SPLIT_ARRAY_TO_SINGLE_ELEMENTS => true,
                ))
            )
        ),
        // store every item
        new \ScraperEngine\Rules\StoreDataRule(
            '',
            array(
                'index_pages_products_formatted_json'
            ),
            array(
                'storage' => new \ScraperEngine\Storage\FileStorage('results')
            )
        ),
    ));
    
    $scraper->execute();

index_pages.xpath content
=========================

    ;; => query('//table[contains(@class, "ad_id")]') || null
    title => current -> query('.//td[contains(@class, "title-cell")]/div/h3') -> item('0') -> __get('nodeValue') || null
    category => current -> query('.//td[contains(@class, "title-cell")]/div/p') -> item('0') -> __get('nodeValue') || null
    product_id => current -> getAttribute('data-id') || null

