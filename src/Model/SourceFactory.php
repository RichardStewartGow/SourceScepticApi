<?php
/**
 * Created by PhpStorm.
 * User: Richard Gow
 * Date: 09/07/2016
 * Time: 16:55
 */

namespace Model;


class SourceFactory
{
    public function makeSource(array $params)
    {
        $source = $this->sourceBuilder($this->getSourceType($params));
        $source->setConnectionParams($params);

        return $source;
    }

    /**
     * @return string $type
     */
    private function getSourceType(array $params)
    {
        $type = '';

        foreach ($params as $key => $value) {
            if ($key === 'type') {
                $type = $value;
            }
        }
        return $type;
    }

    private function sourceBuilder($type)
    {
        $className = "\\Model\\Sources\\" . $type . 'Source';

        if (class_exists($className)) {
            return new $className();
        }

        throw new \Exception('Unable to connect to internal data source');
    }
}