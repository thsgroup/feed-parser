<?php

namespace Thsgroup\FeedParser;

use Thsgroup\FeedParser\Mapper\Mapper;
use Thsgroup\FeedParser\Parser\ParserFactory;
use Thsgroup\FeedParser\Parser\ParserInterface;
use Thsgroup\FeedParser\Validator\ValidatorFiles;
use Thsgroup\FeedParser\Validator\ValidatorParameters;
use Thsgroup\FeedParser\Validator\ValidatorSchema;

class ParsingProcess
{

    protected $validatorParameters;
    protected $validatorFiles;
    protected $validatorSchema;
    protected $parameters;
    protected $variables;
    protected $files;
    protected $output;

    /**
     * ParsingProcess constructor.
     * @param array $parameters
     * @param array $variables
     * @param array $files
     */
    public function __construct($parameters, $variables, $files)
    {
        $this->validatorParameters = new ValidatorParameters();
        $this->validatorFiles = new ValidatorFiles();
        $this->validatorSchema = new ValidatorSchema($parameters);
        $this->parameters = $parameters;
        $this->variables = $variables;
        $this->files = $files;
        $this->output = array();
    }

    public function process()
    {
        $output = null;

        if ($this->validateParametersFiles()) {

            $dataRetriever = new DataRetriever($this->parameters['dirOutput']);
            $dataRetriever->storeFiles($this->files);
            $totalFiles = 0;

            foreach ($dataRetriever->getStoredFiles() as $file) {

                $totalFiles++;

                //TODO: logger implementation
                echo 'PROCESSING FILE: ' . $file . PHP_EOL;
                $rmv3Parser = ParserFactory::create('rmv3');

                if ($rmv3Parser instanceof ParserInterface) {
                    $rmv3Parser->setFilename($file);
                    $data = $rmv3Parser->process();

                    $output = $this->outputData($data, $totalFiles);
                }
            }
        }

        if (!$this->validatorSchema->validate($output)) {
            throw new \RuntimeException('ONE OR MORE PROVIDED FILE CONTAINS ERRORS');
        }

        return $output;
    }

    private function validateParametersFiles()
    {
        if ($this->validatorParameters->validate($this->parameters) && $this->validatorFiles->validate($this->files)) {
            return true;
        }

        //TODO: custom exceptions
        throw new \RuntimeException('INVALID PARAMETERS OR FILES');
    }

    private function processData($data)
    {
        $mapperOut = array();
        $mapperSettings = array();

        foreach ($data as $row) {

            $map = null;

            //TODO: maps need to be assigned to parser format
            if ((integer)$row['TRANS_TYPE_ID'] === 1) {
                $map = include 'Mapper' . DIRECTORY_SEPARATOR . 'Rmv3toAdfSaleMap.php';
                $variables['_channel'] = 1;
            } else if ((integer)$row['TRANS_TYPE_ID'] === 2) {
                $map = include 'Mapper' . DIRECTORY_SEPARATOR . 'Rmv3toAdfRentMap.php';
                $variables['_channel'] = 2;
            }

            if (!empty($map)) {
                $mapper = new Mapper($map, $this->parameters['formatOutput'], $this->variables);
                $mapperRes = $mapper->map($row);

                $mapperSettings = $mapperRes['settings'];
                $mapperRoot = $mapperRes['root'];

                if (!empty($mapperRes['data'])) {
                    $mapperOut[] = $mapperRes['data'];
                } else {
                    $mapperOut[] = $mapperRoot;
                }



                unset($mapper);
            } else {
                throw new \RuntimeException('MAP INVALID OR NOT PROVIDED');
            }
        }

        $res = !empty($mapperOut) ? $mapperOut : null;

        if (!empty($mapperOut) && isset($mapperSettings['data'])) {
            $res[$mapperSettings['data']] = json_decode(json_encode($mapperOut), true);
        }

        return $this->parameters['formatOutput'] === 'adf' ? json_encode($res) : $res;
    }

    private function outputData($data, $ident)
    {
        if (!empty($data) && $this->parameters['formatOutput'] === 'adf' && $this->parameters['filenamePrefixOutput'] === null) {

            $this->output[] = $this->processData($data);

        } else if ($this->parameters['filenamePrefixOutput'] !== null) {

            $json = $this->processData($data);

            $filename = $this->parameters['filenamePrefixOutput'] . '_' . $ident . '.json';

            $outputter = new DataOutputter($this->parameters['dirOutput']);
            $outputter->store($json, $filename);

            $this->output[] = $filename;
        }

        return $this->output;
    }
}
