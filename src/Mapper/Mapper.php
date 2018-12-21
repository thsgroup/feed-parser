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
        if (is_array($data) && $className && $depth === 0) {
            $output = $this->createEntity($data, $className, $lastStringKey);
        } elseif (is_array($data) && $depth) {
            $output = $this->createArray($data, $className, $depth, $lastStringKey);
        } elseif (!is_array($data) && $className && $depth === 0) {
            $output = $this->createInjectedEntity($data, $className);
        } else {
            $output = $data;
        }

        return $output;
    }

    protected function createEntity($data, $className, $lastStringKey)
    {
        if ($factory = $this->getFactoryFunction($className)) {
            $entity = $factory($data, $lastStringKey);
        } else {
            $entity = new $className;
        }
        $className = get_class($entity);
        $reflClass = new ReflectionClass($className);
        foreach ($data as $key => $value) {
            $field = $this->mapField($className, $key);
            $value = $this->hydrate(
                $value,
                $this->getChildClass($field),
                $this->getDepth($field),
                $this->getStringKey($key, $lastStringKey)
            );
            $setter = $this->getSetter($field);
            if($this->allowMethodSetting && is_callable(array($entity, $setter))) {
                $entity->$setter($value);
            } else {
                $property = $this->getProperty($field);
                if ($property && $reflClass->hasProperty($property)) {
                    $reflProp = $reflClass->getProperty($property);
                    $reflProp->setAccessible(true);
                    $reflProp->setValue($entity, $value);
                }
            }
        }
        return $entity;
    }
}
