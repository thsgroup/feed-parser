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

        return $this->parseData();
    }

    private function parseData()
    {
        $this->parseHeader();
        $this->parseDefinition();
        $this->parseBody();

        return $this->prepareOutput();
    }

    private function prepareOutput()
    {
        $result = array();
        $dataRows = str_getcsv($this->dataBody, $this->dataHeader['eor']);

        foreach ($dataRows as $row) {
            if ($row !== null && $row !== '') {

                $row = $this->parseRow($row);

                if (count($row) === $this->dataDefinition['total']) {
                    $result[] = array_combine($this->dataDefinition['columns'], $row);
                }
            }
        }

        return $result;
    }

    private function parseRow($row)
    {
        return array_map('trim', explode($this->dataHeader['eof'], $row));
    }

    private function parseHeader()
    {
        if (!preg_match('/#HEADER#(.*?)#/msi', $this->data, $match)) {
            throw new \RuntimeException('Not valid BLM file, header section not found');
        }

        $headerElements = explode(PHP_EOL, trim($match[1]));

        foreach ($headerElements as $row) {
            list($key, $value) = explode(':', $row);
            $this->dataHeader[strtolower(trim($key))] = trim(preg_replace("/['\"]/", '', $value));
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

        $definitions = array_map('trim', explode($this->dataHeader['eof'], $match[1]));

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
