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
     * @var array mixed
     */
    private $settings = array();

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
     * @param array                $settings
     * @param array                $rules
     * @param LoggerInterface|null $logger
     * @throws ScraperEngineException
     */
    public function __construct($settings = array(), array $rules = array(), LoggerInterface $logger = null)
    {
        $this->settings   = $settings;
        $this->rules      = $rules;
        $this->logger     = ($logger) ? $logger : new DefaultLogger();
        $this->tempPrefix = isset($settings['tempPrefix']) ? $settings['tempPrefix'] : '';

        if (isset($settings['strict-errors']) && $settings['strict-errors']) {
            $this->setErrorHandler();
        }

        if (!isset($settings['tempPath'])) {
            $this->prepareTempDir();
        } else {
            if (!file_exists($settings['tempPath'])) {
                throw new ScraperEngineException(sprintf('Temp path %s not exists', $settings['tempPath']));
            }
            static::$tempPath = $settings['tempPath'];
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

            // add default settings
            $settings = $rule->getSettings();
            if (!$settings) {
                $settings = array();
            }

            if (!isset($settings['loader']) && isset($this->settings['loader'])) {
                $class = $this->settings['loader']['class'];
                $settings['loader'] = new $class;
            }
            if (!isset($settings['response_class']) && isset($this->settings['response'])) {
                $settings['response_class'] = $this->settings['response']['class'];
            }
            if (!isset($settings['request_class']) && isset($this->settings['request'])) {
                $settings['request_class'] = $this->settings['request']['class'];
            }

            $settings['variables'] = isset($settings['variables']) ? array_merge($this->settings['variables'], $settings['variables']) : $this->settings['variables'];

            $rule->configure($settings);
            $this->storage[$rule->getName()] = $rule->execute($storage);
            $settings = null;
            unset($settings);

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
     * Set error handler
     */
    private function setErrorHandler()
    {
        set_error_handler(array($this, 'errHandle'));
    }

    /**
     * @param int    $errNo
     * @param string $errStr
     * @param string $errFile
     * @param string $errLine
     * @throws ScraperEngineException
     */
    public function errHandle($errNo, $errStr, $errFile, $errLine) {
        $msg = "$errStr in $errFile on line $errLine";
        if ($errNo == E_NOTICE || $errNo == E_WARNING) {
            throw new ScraperEngineException($msg, $errNo);
        } else {
            echo $msg;
        }
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
