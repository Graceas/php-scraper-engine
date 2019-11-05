<?php
/**
 * Created by PhpStorm.
 * User: gorelov
 * Date: 2019-10-20
 * Time: 00:33
 */

namespace ScraperEngine\Parser;

use ScraperEngine\Exception\ScraperEngineException;
use ScraperEngine\Helper\ArrayHelper;

/**
 * Class JsonToArrayParser
 * @package ScraperEngine\Parser
 */
class JsonToArrayParser implements ParserInterface
{
    const INSTRUCTION_VALUE_KEYS    = 'instruction_values_key';
    const INSTRUCTION_VALUE_DEFAULT = 'default';
    const INSTRUCTION_TYPE          = 'type';
    const INSTRUCTION_TYPE_VALUE    = 'type_value';
    const INSTRUCTION_TYPE_CYCLE    = 'type_cycle';
    const INSTRUCTION_INSTRUCTIONS  = 'instructions';

    /**
     * @param string $content
     * @param array  $settings [instructions => '']
     * @return mixed
     * @throws ScraperEngineException
     */
    public function &parse(&$content, $settings = array())
    {
        $data = json_decode($content, true);

        $content = null;
        unset($content);

        $result = array();
        $instructions = $settings[self::INSTRUCTION_INSTRUCTIONS];
        $instructionsPrepared = array();

        for ($i = 0; $i < count($instructions); $i++) {
            if (empty(trim($instructions[$i]))) {
                continue;
            }

            @list($key, $commands) = explode(' => ', $instructions[$i], 2);
            if (!$key || !$commands) {
                throw new ScraperEngineException(sprintf('Instruction %s incorrect', $instructions[$i]));
            }
            list($commands, $default) = explode(' || ', $commands, 2);
            $commands = explode(' -> ', $commands);

            if (strpos($key, ';;') !== false) {
                $key = str_replace(';;', '', $key);

                $cycleKeyValue = $commands;
                $cycleKey      = $key;
                $cycleDefault  = $default;

                $cycleInstruction = array();
                for ($j = $i + 1; $j < count($instructions); $j++) {
                    if ($instructions[$j] == 'end') {
                        $i = $j + 1;

                        break;
                    }

                    list($key, $commands) = explode(' => ', $instructions[$j], 2);
                    list($commands, $default) = explode(' || ', $commands, 2);
                    $commands = explode(' -> ', $commands);

                    $cycleInstruction[$key] = array(
                        self::INSTRUCTION_TYPE          => self::INSTRUCTION_TYPE_VALUE,
                        self::INSTRUCTION_VALUE_KEYS    => $commands,
                        self::INSTRUCTION_VALUE_DEFAULT => $default
                    );
                }

                $instructionsPrepared[$cycleKey] = array(
                    self::INSTRUCTION_TYPE          => self::INSTRUCTION_TYPE_CYCLE,
                    self::INSTRUCTION_VALUE_KEYS    => $cycleKeyValue,
                    self::INSTRUCTION_VALUE_DEFAULT => $cycleDefault,
                    self::INSTRUCTION_INSTRUCTIONS  => $cycleInstruction,
                );

                continue;
            }

            $instructionsPrepared[$key] = array(
                self::INSTRUCTION_TYPE          => self::INSTRUCTION_TYPE_VALUE,
                self::INSTRUCTION_VALUE_KEYS    => $commands,
                self::INSTRUCTION_VALUE_DEFAULT => $default
            );
        }

        foreach ($instructionsPrepared as $key => $instruction) {
            if ($instruction[self::INSTRUCTION_TYPE] == self::INSTRUCTION_TYPE_VALUE) {
                if (in_array($key, array('_source_url', '_loaded_date'))) {
                    $result[$key] = $settings[$key];
                } else {
                    $result[$key] = ArrayHelper::getValueByKeys($data, $instruction[self::INSTRUCTION_VALUE_KEYS], $instruction[self::INSTRUCTION_VALUE_DEFAULT]);
                }
            }
            if ($instruction[self::INSTRUCTION_TYPE] == self::INSTRUCTION_TYPE_CYCLE) {
                $result[$key] = array();

                $sourceData = ArrayHelper::getValueByKeys($data, $instruction[self::INSTRUCTION_VALUE_KEYS], $instruction[self::INSTRUCTION_VALUE_DEFAULT]);

                foreach ($sourceData as $itemData) {
                    $item = array();
                    foreach ($instruction[self::INSTRUCTION_INSTRUCTIONS] as $cycleItemKey => $cycleInstruction) {
                        if (count($cycleInstruction[self::INSTRUCTION_VALUE_KEYS]) > 0 && $cycleInstruction[self::INSTRUCTION_VALUE_KEYS][0] == '$current') {
                            $keys = $cycleInstruction[self::INSTRUCTION_VALUE_KEYS];
                            array_shift($keys);

                            $item[$cycleItemKey] = ArrayHelper::getValueByKeys($itemData, $keys, $cycleInstruction[self::INSTRUCTION_VALUE_DEFAULT]);
                        } else if (in_array($cycleItemKey, array('_source_url', '_loaded_date'))) {
                            $item[$cycleItemKey] = $settings[$cycleItemKey];
                        } else {
                            $item[$cycleItemKey] = ArrayHelper::getValueByKeys($data, $cycleInstruction[self::INSTRUCTION_VALUE_KEYS], $cycleInstruction[self::INSTRUCTION_VALUE_DEFAULT]);
                        }
                    }

                    $result[$key][] = $item;
                }

                $sourceData = null;
                unset($sourceData);

            }
        }

        $data = null;
        $instructionsPrepared = null;
        $instructions = null;
        unset($data, $instructionsPrepared, $instructions);

        return $result;
    }
}
