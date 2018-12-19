<?php

namespace Thsgroup\FeedParser\Parser;

class Rmv3Parser implements ParserInterface
{
    private $data;
    private $filename;
    private $dataHeader;
    private $dataDefinition;
    private $dataBody;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function process()
    {
        $this->isValidExtension();
        $this->setData(file_get_contents($this->filename));

        $this->parseData();
    }

    private function parseData()
    {
        $this->parseHeader();
        $this->parseDefinition();
        $this->parseBody();

        $outputArr = $this->prepareOutput();
        var_dump($outputArr);
    }

    private function prepareOutput()
    {
        $data = str_getcsv($this->dataBody, $this->dataHeader['eof'], $this->dataHeader['eor']);

        array_walk($data, function (&$field) use ($data) {
            $field = array_combine($data[0], $field);
        });

        array_shift($data);

        return $data;
    }

    private function parseHeader()
    {
        if (!preg_match('/#HEADER#(.*?)#/msi', $this->data, $match)) {
            throw new \RuntimeException('Not valid BLM file, header section not found');
        }

        $headerElements = explode(PHP_EOL, trim($match[1]));

        foreach ($headerElements as $row) {
            list($key, $value) = explode(':', $row);
            $this->dataHeader[strtolower(trim($key))] = trim($value, ' \'');
        }

        if (!isset($this->dataHeader['version'])) {
            throw new \RuntimeException('Not valid BLM file, version definition not found');
        }
        if (!isset($this->dataHeader['eof'])) {
            throw new \RuntimeException('Not valid BLM file, EOF definition not found');
        }
        if (!isset($this->dataHeader['eor'])) {
            throw new \RuntimeException('Not valid BLM file, EOR definition not found');
        }
    }

    private function parseDefinition()
    {
        if (!preg_match('/#DEFINITION#(.*?)#/msi', $this->data, $match)) {
            throw new \RuntimeException('Not valid BLM file, definition section not found');
        }

        $definitions = array_map(
            'trim',
            array_filter(explode($this->dataHeader['eor'], trim($match[1])))
        );
        $this->dataDefinition['total'] = count($definitions);

        if ($this->dataDefinition['total'] === 0) {
            throw new \RuntimeException('Not valid BLM file, field definitions invalid or empty');
        }

        $this->dataDefinition['columns'] = $definitions;
    }

    private function parseBody()
    {
        if (!preg_match('/#DATA#(.*?)#END#/msi', $this->data, $match)) {
            throw new \RuntimeException('Not valid BLM file, data section not found');
        }

        $this->dataBody = $match[1];
        //no longer need this, free some mem
        unset($this->data);
    }

    private function isValidExtension()
    {
        $parts = explode('.', $this->filename);
        $extension = strtolower(end($parts));

        if ($extension !== 'blm') {
            throw new \RuntimeException('Not valid file extension: ' . $extension);
        }
    }

}
