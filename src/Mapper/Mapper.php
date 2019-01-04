<?php

namespace Thsgroup\FeedParser\Mapper;

class Mapper
{
    protected $map;
    protected $outputFormat;
    protected $variables;

    /**
     * Mapper constructor,
     * @param array $map
     * @param $outputFormat
     * @param array $variables
     */
    public function __construct($map, $outputFormat, $variables)
    {
        $this->map = $map;
        $this->outputFormat = $outputFormat;
        $this->variables = $variables;
    }

    /**
     * Map single data row to new array map
     * @param $data
     * @return mixed
     */
    public function map($data)
    {
        $data = array_merge($data, $this->variables);

        foreach ($data as $key => $val) {
            $this->map[$this->outputFormat] = $this->recursiveArrayReplace($key, $val, $this->map[$this->outputFormat]);
        }

        $this->map[$this->outputFormat] = $this->removeEmptyElements($this->map[$this->outputFormat]);
        return $this->map[$this->outputFormat];
    }

    /**
     * Remove all the mapping elements from map
     * @param array $array
     * @return array
     */

    protected function clearArray($array)
    {
        $pattern = '/\#(.*?)\#/';

        if (is_array($array)) {
            foreach ($array as &$value) {
                $value = $this->clearArray($value);
            }
            return $array;
        }

        return preg_replace($pattern, '', $array, -1);
    }

    /**
     * Remove empty array elements (for example when no pictures provided)
     * @param $haystack
     * @return mixed
     */
    public function removeEmptyElements($haystack)
    {
        $pattern = '/\#(.*?)\#/';

        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->removeEmptyElements($haystack[$key]);
            } else {
                $haystack[$key] = preg_replace($pattern, '', $haystack[$key], -1);
            }

            if (empty($haystack[$key])) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }

    /**
     * Replace definitions with values within mapping array
     * @param $find
     * @param $replace
     * @param $array
     * @return array|mixed
     */
    protected function recursiveArrayReplace($find, $replace, $array)
    {
        if (!is_array($array)) {
            return str_replace('#' . $find . '#', $replace, $array);
        }

        $newArray = array();

        foreach ($array as $key => $value) {
            $newArray[$key] = $this->recursiveArrayReplace($find, $replace, $value);
        }
        return $newArray;
    }
}
