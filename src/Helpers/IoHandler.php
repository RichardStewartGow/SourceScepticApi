<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 15:56
 */

namespace Helpers;

use Model\Constants as Constants;
use Symfony\Component\Yaml\Yaml as Yaml;
use Symfony\Component\Yaml\Exception\ParseException as ParseException;
use Model\SourceFactory as SourceFactory;

class IoHandler
{
    /**
     * @var Yaml
     */
    private $yamlConfigParser;

    /**
     * @var SourceFactory
     */
    private $sourceFactory;

    /**
     * @return Yaml
     */
    public function getYamlConfigParser()
    {
        if (!$this->yamlConfigParser){
            $this->yamlConfigParser = new Yaml();
        }

        return $this->yamlConfigParser;
    }

    /**
     * @param Yaml $yamlConfigParser
     * @return IoHandler
     */
    public function setYamlConfigParser($yamlConfigParser)
    {
        $this->yamlConfigParser = $yamlConfigParser;
        return $this;
    }

    /**
     * @return SourceFactory
     */
    public function getSourceFactory()
    {
        if (!$this->sourceFactory) {
            $this->sourceFactory = new SourceFactory();
        }

        return $this->sourceFactory;
    }

    /**
     * @param SourceFactory $sourceFactory
     * @return IoHandler
     */
    public function setSourceFactory($sourceFactory)
    {
        $this->sourceFactory = $sourceFactory;
        return $this;
    }

    /**
     * @return array $response
     * @throws \Exception
     */
    public function createConnection()
    {
        $targetSource = $this->returnActiveSource();
        try {
            $source = $this->getSourceFactory()->makeSource($targetSource);
        } catch(\Exception $e) {
            throw new \Exception('Internal source configuration error');
        }

        return $source;
    }

    public function returnActiveSource()
    {
        $yamlParser = $this->getYamlConfigParser();

        $target = Constants::SOURCES_CONFIG_PATH;

        try {
            $sources = $yamlParser::parse(file_get_contents($target));
            $activeSource = $this->parseSources($sources);
            return $activeSource;
        } catch (ParseException $e) {
            return Constants::TYPE_ERROR;
        }
    }

    private function parseSources(array $config)
    {
        $activeSource = array();

        //Iterate through, find the first active source.
        if (array_key_exists('sources', $config)) {
            $sources = $config['sources'];
            foreach ($sources as $sourceName => $subArray) {
                if (
                    array_key_exists('active', $subArray)
                    && $subArray['active'] === 'yes'
                ) {
                    $activeSource = $subArray;
                    break;
                }
            }
        }

        return $activeSource;
    }
}