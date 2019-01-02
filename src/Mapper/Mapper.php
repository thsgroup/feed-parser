<?php

namespace Thsgroup\FeedParser\Mapper;

use ReflectionClass;

class Mapper
{
    protected $map;

    /**
     * Mapper constructor.
     * @param array $map
     */
    public function __construct($map)
    {
        $this->map = $map;
    }

    private function map($sourceArr)
    {

    }

    public function hydrate($data, $className = null, $depth = 0, $lastStringKey = null)
    {
        if ($className && is_array($data) && $depth === 0) {
            $output = $this->createEntity($data, $className, $lastStringKey);
        } else if ($depth !== 0 && is_array($data)) {
            $output = $this->createArray($data, $className, $depth, $lastStringKey);
        } else if (!is_array($data) && $className && $depth === 0) {
            $output = $this->createInjectedEntity($data, $className);
        } else {
            $output = $data;
        }

        return $output;
    }

    protected function createEntity($data, $className, $lastStringKey)
    {
        $entity = new $className;

        $className = get_class($entity);
        $reflectionClass = new ReflectionClass($className);

        foreach ($data as $key => $value) {

            $field = $this->mapField($className, $key);
            $value = $this->hydrate(
                $value,
                $this->getChildClass($field),
                $this->getDepth($field),
                $this->getStringKey($key, $lastStringKey)
            );

            $setter = $this->getSetter($field);
            if (is_callable(array($entity, $setter))) {
                $entity->$setter($value);
            } else {
                $property = $this->getProperty($field);
                if ($property && $reflectionClass->hasProperty($property)) {
                    $reflectionProp = $reflectionClass->getProperty($property);
                    $reflectionProp->setAccessible(true);
                    $reflectionProp->setValue($entity, $value);
                }
            }
        }
        return $entity;
    }

    protected function createInjectedEntity($data, $className)
    {
        try {
            return new $className($data);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function createArray($data, $className, $depth, $lastStringKey)
    {
        $newArray = array();
        $depth--;
        foreach ($data as $key => $value) {
            $newArray[$key] = $this->hydrate($value, $className, $depth, $this->getStringKey($key, $lastStringKey));
        }
        return $newArray;
    }

    protected function getStringKey($key, $lastStringKey)
    {
        return is_string($key) ? $key : $lastStringKey;
    }

    protected function mapField($className, $key)
    {
        return isset($this->map[$className][$key]) ? $this->map[$className][$key] : array('name' => $key);
    }

    protected function getChildClass($field)
    {
        return isset($field['class']) ? $field['class'] : null;
    }

    protected function getDepth($field)
    {
        return isset($field['depth']) ? $field['depth'] : 0;
    }

    protected function getSetter($field)
    {
        return 'set' . $this->getProperty($field);
    }

    protected function getProperty($field)
    {
        return isset($field['name']) ? $field['name'] : null;
    }
}
