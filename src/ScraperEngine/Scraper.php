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
use ScraperEngine\Rules\ClearRule;
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
    public static $tempPath = '';

    /**
     * @var string
     */
    protected $tempPrefix = '';

    /**
     * Scraper constructor.
     * @param array                $rules
     * @param LoggerInterface|null $logger
     * @param string               $tempPath
     * @param string               $tempPrefix
     * @throws ScraperEngineException
     */
    public function __construct(array $rules = array(), LoggerInterface $logger = null, $tempPath = null, $tempPrefix = '')
    {
        $this->rules      = $rules;
        $this->logger     = ($logger) ? $logger : new DefaultLogger();
        $this->tempPrefix = $tempPrefix;

        if (!$tempPath) {
            $this->prepareTempDir();
        } else {
            if (!file_exists($tempPath)) {
                throw new ScraperEngineException(sprintf('Temp path %s not exists', $tempPath));
            }
            static::$tempPath = $tempPath;
        }
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

                $this->rules[$rule->getRequired()[0]]   = null;
            }

            if ($rule instanceof ClearRule) {
                foreach ($rule->getRequired() as $required) {
                    $this->storage[$required] = null;

                    unset($this->storage[$required]);
                }

                gc_collect_cycles();
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
        system('rm -rf '.escapeshellarg(static::$tempPath), $retVal);
    }

    /**
     * Prepare temp dir.
     */
    private function prepareTempDir()
    {
        $pid = $this->tempPrefix.''.getmypid();
        $loaderTempDir = sys_get_temp_dir().'/_loader_/';
        static::$tempPath = $loaderTempDir.$pid.'/';
        if (!file_exists($loaderTempDir)) {
            mkdir($loaderTempDir);
        }
        if (!file_exists(static::$tempPath)) {
            mkdir(static::$tempPath);
        }

        register_shutdown_function(array($this, '__destruct'));
    }

    /**
     * @return string
     */
    public function getTempPath()
    {
        return static::$tempPath;
    }

    /**
     * @param array $rules
     */
    public function setRules(&$rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param array $storage
     */
    public function setStorage(&$storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }
}
