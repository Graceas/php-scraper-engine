<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-19
 * Time: 21:44
 */

namespace ScraperEngine;

use ScraperEngine\Exception\ScraperEngineException;
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
     * Scraper constructor.
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @throws ScraperEngineException
     */
    public function execute()
    {
        foreach ($this->rules as $rule) {
            /** @var RuleInterface $rule */
            $storage = array();
            foreach ($rule->getRequired() as $required) {
                if (!isset($this->storage[$required])) {
                    throw new ScraperEngineException(printf('Variable %s not found in the storage', $required));
                }
                $storage[$required] = $this->storage[$required];
            }
            $this->storage[$rule->getName()] = $rule->execute($storage);

            unset($storage);
        }
    }
}
