<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 21:44
 */

namespace ScraperEngine;

use ScraperEngine\Exception\ScraperEngineException;
use ScraperEngine\Logger\DefaultLogger;
use ScraperEngine\Logger\LoggerInterface;
use ScraperEngine\Rules\RuleInterface;

/**
 * Class Scraper
 * @package ScraperEngine
 */
class Scraper
{
    /**
     * @var array RulesInterface[]
     */
    private $rules = array();

    /**
     * @var array mixed
     */
    private $storage = array();

    /**
     * @var LoggerInterface|null
     */
    private $logger = null;

    /**
     * Scraper constructor.
     * @param array $rules
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $rules, LoggerInterface $logger = null)
    {
        $this->rules  = $rules;
        $this->logger = ($logger) ? $logger : new DefaultLogger();
    }

    /**
     * @throws ScraperEngineException
     */
    public function execute()
    {
        /** @var RuleInterface $rule */
        foreach ($this->rules as $rule) {
            $this->logger->addInfo(sprintf('Execute %s rule - started', $rule->getName()));
            $storage = array();
            foreach ($rule->getRequired() as $required) {
                if (!isset($this->storage[$required])) {
                    throw new ScraperEngineException(sprintf('Variable %s not found in the storage', $required));
                }
                $storage[$required] = $this->storage[$required];
            }
            $this->storage[$rule->getName()] = $rule->execute($storage);

            $this->logger->addInfo(sprintf('Execute %s rule - finished', $rule->getName()));
            unset($storage);
        }
    }
}
