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
use ScraperEngine\Rules\ParseResponsesRule;
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
     * @var string
     */
    private $tempPath = '';

    /**
     * Scraper constructor.
     * @param array $rules
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $rules = array(), LoggerInterface $logger = null)
    {
        $this->rules  = $rules;
        $this->logger = ($logger) ? $logger : new DefaultLogger();

        $this->prepareTempDir();
    }

    /**
     * @throws ScraperEngineException
     */
    public function execute()
    {
        gc_disable();

        /** @var RuleInterface $rule */
        foreach ($this->rules as &$rule) {
            if (!$rule) {
                continue;
            }

            $this->logger->addInfo(sprintf('Execute %s rule - started', $rule->getName()));
            $storage = array();
            foreach ($rule->getRequired() as $required) {
                if (!isset($this->storage[$required])) {
                    throw new ScraperEngineException(sprintf('Variable %s not found in the storage', $required));
                }
                $storage[$required] = $this->storage[$required];
            }
            $this->storage[$rule->getName()] = $rule->execute($storage);

            if ($rule instanceof ParseResponsesRule) {
                // clear responses
                if (isset($this->storage[$rule->getRequired()[0]]) && is_array($this->storage[$rule->getRequired()[0]])) {
                    foreach ($this->storage[$rule->getRequired()[0]] as &$response) {
                        $response = null;
                    }
                }

//                $this->storage[$rule->getRequired()[0]] = null;
//                $this->rules[$rule->getRequired()[0]]   = null;
            }

            $this->logger->addInfo(sprintf('Execute %s rule - finished', $rule->getName()));
            $rule    = null;
            $storage = null;
        }
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        @rmdir($this->tempPath);
    }

    /**
     * Prepare temp dir.
     */
    private function prepareTempDir()
    {
        $pid = getmypid();
        $loaderTempDir = sys_get_temp_dir().'/_loader_/';
        $this->tempPath = $loaderTempDir.$pid.'/';
        if (!file_exists($loaderTempDir)) {
            mkdir($loaderTempDir);
        }
        if (!file_exists($this->tempPath)) {
            mkdir($this->tempPath);
        }

        register_shutdown_function(array($this, '__destruct'));
    }

    /**
     * @return string
     */
    public function getTempPath()
    {
        return $this->tempPath;
    }

    /**
     * @param array $rules
     */
    public function setRules(&$rules)
    {
        $this->rules = $rules;
    }
}
