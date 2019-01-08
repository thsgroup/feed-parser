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

        $this->map[$this->outputFormat] = $this->updateIterativeSubArrays($data, $this->map[$this->outputFormat]);

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
        $newArray = array();

        if (!is_array($array)) {
            return str_replace('#' . $find . '#', $replace, $array);
        }

        foreach ($array as $key => $value) {

            $newArray[$key] = $this->recursiveArrayReplace($find, $replace, $value);
        }

        return $newArray;
    }

    /**
     * @param $data
     * @param $formattedArray
     * @return mixed
     */
    protected function updateIterativeSubArrays($data, $formattedArray)
    {
        $newArray = $formattedArray;

        foreach ($formattedArray as $key => $val) {

            if (is_array($val) && preg_match('/@(.*?)@/', $key) === 1) {

                //iterative element found, we need to create subarray
                $elements = explode('--', str_replace('@', '', $key));
                $subArray = array();
                $existingIdents = array();

                foreach ($val as $subKey => $subVal) {

                    //TODO: find all integers used on iterative elements like MEDIA_IMAGE_01-99. Then use it instead of 0-99 loop

                    for ($i = 0; $i <= 99; $i++) {

                        $newKey = str_replace('#', '', $subVal) . str_pad($i, 2, 0, STR_PAD_LEFT);

                        if (isset($data[$newKey]) && $data[$newKey] !== '') {
                            $subArray[$i][$subKey] = $data[$newKey];
                            $existingIdents[] = $i;
                        } else if ($subVal !== '' && substr_count($subVal, '#') < 2) {
                            $subArray[$i][$subKey] = $subVal;
                        }
                    }
                }

                $this->tidySubArray($subArray, $existingIdents);

                $newArray[$elements[0]] = $subArray;
                unset($newArray[$key]);
            }

            if (is_array($val) && preg_match('/@(.*?)@/', $key) !== 1) {

                $newArray[$key] = $this->updateIterativeSubArrays($data, $val);
            }
        }

        return $newArray;
    }

    /**
     * Remove empty elements from sub array (for example media)
     * @param $subArray
     * @param $existingIdents
     * @return array
     */
    protected function tidySubArray($subArray, $existingIdents)
    {
        return array_filter($subArray, function ($key) use ($existingIdents, $subArray) {

            return !(isset($subArray[$key]) && !in_array($key, $existingIdents, true));
        }, ARRAY_FILTER_USE_KEY);
    }
}
