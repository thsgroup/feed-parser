<?php

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class ParsingProcessTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $filesystem;

    /**
     * @var string $dirOutput
     */
    private $dirOutput;

    public function setUp()
    {
        $this->dirOutput = 'data/out';
        $this->filesystem = vfsStream::setup('data');

        $blmContent = <<<EOT
#HEADER#
Version : 3
EOF : '|'
EOR : '~'
#DEFINITION#
AGENT_REF|ADDRESS_1|ADDRESS_2|ADDRESS_3|TOWN|POSTCODE1|POSTCODE2|FEATURE1|SUMMARY|DESCRIPTION|BRANCH_ID|STATUS_ID|BEDROOMS|BATHROOMS|LIVING_ROOMS|PRICE|PRICE_QUALIFIER|PROP_SUB_ID|CREATE_DATE|UPDATE_DATE|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|ADMINISTRATION_FEE|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TENURE_TYPE_ID|TRANS_TYPE_ID|MIN_SIZE_ENTERED|MAX_SIZE_ENTERED|AREA_SIZE_UNIT_ID|NEW_HOME_FLAG|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_FLOOR_PLAN_00|~
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|1|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-sale.blm')->at($this->filesystem)->setContent($blmContent);

        $blmContent = <<<EOT
#HEADER#
Version : 3
EOF : '|'
EOR : '~'
#DEFINITION#
AGENT_REF|ADDRESS_1|ADDRESS_2|ADDRESS_3|TOWN|POSTCODE1|POSTCODE2|FEATURE1|SUMMARY|DESCRIPTION|BRANCH_ID|STATUS_ID|BEDROOMS|BATHROOMS|LIVING_ROOMS|PRICE|PRICE_QUALIFIER|PROP_SUB_ID|CREATE_DATE|UPDATE_DATE|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|ADMINISTRATION_FEE|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TENURE_TYPE_ID|TRANS_TYPE_ID|MIN_SIZE_ENTERED|MAX_SIZE_ENTERED|AREA_SIZE_UNIT_ID|NEW_HOME_FLAG|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_FLOOR_PLAN_00|~
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|2|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-rent.blm')->at($this->filesystem)->setContent($blmContent);

        $blmContent = <<<EOT
#DEFINITION#
AGENT_REF|ADDRESS_1|ADDRESS_2|ADDRESS_3|TOWN|POSTCODE1|POSTCODE2|FEATURE1|SUMMARY|DESCRIPTION|BRANCH_ID|STATUS_ID|BEDROOMS|BATHROOMS|LIVING_ROOMS|PRICE|PRICE_QUALIFIER|PROP_SUB_ID|CREATE_DATE|UPDATE_DATE|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|ADMINISTRATION_FEE|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TENURE_TYPE_ID|TRANS_TYPE_ID|MIN_SIZE_ENTERED|MAX_SIZE_ENTERED|AREA_SIZE_UNIT_ID|NEW_HOME_FLAG|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_FLOOR_PLAN_00|~
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|2|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-rent-invalidheader.blm')->at($this->filesystem)->setContent($blmContent);

        $blmContent = <<<EOT
#HEADER#
EOF : '|'
EOR : '~'
#DEFINITION#
AGENT_REF|ADDRESS_1|ADDRESS_2|ADDRESS_3|TOWN|POSTCODE1|POSTCODE2|FEATURE1|SUMMARY|DESCRIPTION|BRANCH_ID|STATUS_ID|BEDROOMS|BATHROOMS|LIVING_ROOMS|PRICE|PRICE_QUALIFIER|PROP_SUB_ID|CREATE_DATE|UPDATE_DATE|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|ADMINISTRATION_FEE|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TENURE_TYPE_ID|TRANS_TYPE_ID|MIN_SIZE_ENTERED|MAX_SIZE_ENTERED|AREA_SIZE_UNIT_ID|NEW_HOME_FLAG|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_FLOOR_PLAN_00|~
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|2|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-rent-invalidheaderversion.blm')->at($this->filesystem)->setContent($blmContent);

        $blmContent = <<<EOT
#HEADER#
Version : 3
EOR : '~'
#DEFINITION#
AGENT_REF|ADDRESS_1|ADDRESS_2|ADDRESS_3|TOWN|POSTCODE1|POSTCODE2|FEATURE1|SUMMARY|DESCRIPTION|BRANCH_ID|STATUS_ID|BEDROOMS|BATHROOMS|LIVING_ROOMS|PRICE|PRICE_QUALIFIER|PROP_SUB_ID|CREATE_DATE|UPDATE_DATE|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|ADMINISTRATION_FEE|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TENURE_TYPE_ID|TRANS_TYPE_ID|MIN_SIZE_ENTERED|MAX_SIZE_ENTERED|AREA_SIZE_UNIT_ID|NEW_HOME_FLAG|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_FLOOR_PLAN_00|~
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|2|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-rent-invalidheadereof.blm')->at($this->filesystem)->setContent($blmContent);

        $blmContent = <<<EOT
#HEADER#
Version : 3
EOF : '|'
#DEFINITION#
AGENT_REF|ADDRESS_1|ADDRESS_2|ADDRESS_3|TOWN|POSTCODE1|POSTCODE2|FEATURE1|SUMMARY|DESCRIPTION|BRANCH_ID|STATUS_ID|BEDROOMS|BATHROOMS|LIVING_ROOMS|PRICE|PRICE_QUALIFIER|PROP_SUB_ID|CREATE_DATE|UPDATE_DATE|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|ADMINISTRATION_FEE|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TENURE_TYPE_ID|TRANS_TYPE_ID|MIN_SIZE_ENTERED|MAX_SIZE_ENTERED|AREA_SIZE_UNIT_ID|NEW_HOME_FLAG|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_FLOOR_PLAN_00|~
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|2|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-rent-invalidheadereor.blm')->at($this->filesystem)->setContent($blmContent);


        $blmContent = <<<EOT
#HEADER#
Version : 3
EOF : '|'
EOR : '~'
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|2|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-rent-invaliddefinition.blm')->at($this->filesystem)->setContent($blmContent);

        $blmContent = <<<EOT
#HEADER#
Version : 3
EOF : '|'
EOR : '~'
#DEFINITION#
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|2|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-rent-invaliddefinitionfields.blm')->at($this->filesystem)->setContent($blmContent);

        $blmContent = <<<EOT
#HEADER#
Version : 3
EOF : '|'
EOR : '~'
#DEFINITION#
AGENT_REF|ADDRESS_1|ADDRESS_2|ADDRESS_3|TOWN|POSTCODE1|POSTCODE2|FEATURE1|SUMMARY|DESCRIPTION|BRANCH_ID|STATUS_ID|BEDROOMS|BATHROOMS|LIVING_ROOMS|PRICE|PRICE_QUALIFIER|PROP_SUB_ID|CREATE_DATE|UPDATE_DATE|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|ADMINISTRATION_FEE|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TENURE_TYPE_ID|TRANS_TYPE_ID|MIN_SIZE_ENTERED|MAX_SIZE_ENTERED|AREA_SIZE_UNIT_ID|NEW_HOME_FLAG|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_FLOOR_PLAN_00|~
#END#
EOT;

        vfsStream::newFile('data/test-rent-invaliddata.blm')->at($this->filesystem)->setContent($blmContent);

        $blmContent = <<<EOT
#HEADER#
Version : 3
EOF : '|'
EOR : '~'
#DEFINITION#
AGENT_REF|ADDRESS_1|ADDRESS_2|ADDRESS_3|TOWN|POSTCODE1|POSTCODE2|FEATURE1|SUMMARY|DESCRIPTION|BRANCH_ID|STATUS_ID|BEDROOMS|BATHROOMS|LIVING_ROOMS|PRICE|PRICE_QUALIFIER|PROP_SUB_ID|CREATE_DATE|UPDATE_DATE|DISPLAY_ADDRESS|PUBLISHED_FLAG|LET_DATE_AVAILABLE|LET_BOND|ADMINISTRATION_FEE|LET_TYPE_ID|LET_FURN_ID|LET_RENT_FREQUENCY|TENURE_TYPE_ID|TRANS_TYPE_ID|MIN_SIZE_ENTERED|MAX_SIZE_ENTERED|AREA_SIZE_UNIT_ID|NEW_HOME_FLAG|MEDIA_IMAGE_00|MEDIA_IMAGE_01|MEDIA_FLOOR_PLAN_00|~
#DATA#
123456_01|123|Main Road|London|London|TQ6|9EG|Test Feature|Sample Summary|Sample Description|123456|3|3|1|1|100000|4|1|2018-12-13 12:00:00|2018-12-13 12:01:00|123 Main Road, London|1|||||||1|2|0|0|2|N|123456_01_IMG_00.jpg|123456_01_IMG_01.jpg|123456_01_IMG_FP.jpg|~
#END#
EOT;

        vfsStream::newFile('data/test-rent-invalid.not')->at($this->filesystem)->setContent($blmContent);
    }

    public function tearDown()
    {
        $this->removeDirectory($this->dirOutput);
    }

    public function testProcessValidParameters()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => 'test',
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false === strpos($file->getName(), 'invalid')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $res = $parsingProcess->process();

        $this->assertCount(2, $res);
    }

    public function testProcessInvalidParameters()
    {
        $parameters = array(
            'formatInput' => 'invalid',
            'formatOutput' => 'invalid',
            'dirOutput' => null,
            'filenamePrefixOutput' => null,
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            $files[] = $file->url();
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessInvalidData()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => 'test',
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            $files[] = $file->url() . '-invalid';
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessValidParametersJsonResponse()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => null,
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false === strpos($file->getName(), 'invalid')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $res = $parsingProcess->process();

        $this->assertCount(2, $res);
    }

    public function testProcessInvalidBlmHeader()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => null,
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false !== strpos($file->getName(), 'invalidheader')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessInvalidBlmHeaderVersion()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => null,
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false !== strpos($file->getName(), 'invalidheaderversion')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessInvalidBlmHeaderEof()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => null,
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false !== strpos($file->getName(), 'invalidheadereof')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessInvalidBlmHeaderEor()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => null,
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false !== strpos($file->getName(), 'invalidheadereor')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessInvalidBlmDefinition()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => '_test',
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false !== strpos($file->getName(), 'invaliddefinition')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessInvalidBlmDefinitionFields()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => '_test',
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false !== strpos($file->getName(), 'invaliddefinitionfields')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessInvalidBlmData()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => '_test',
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false !== strpos($file->getName(), 'invaliddata')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    public function testProcessInvalidBlmExtension()
    {
        $parameters = array(
            'formatInput' => 'rmv3',
            'formatOutput' => 'adf',
            'dirOutput' => $this->dirOutput,
            'filenamePrefixOutput' => '_test',
        );

        $variables = array(
            '_network_id' => 123,
            '_overseas' => null,
            '_media_type_image' => 1,
        );

        $files = array();

        foreach ($this->filesystem->getChildren() as $file) {
            if (false !== strpos($file->getName(), '.not')) {
                $files[] = $file->url();
            }
        }

        foreach ($files as $file) {
            $this->assertFileExists($file);
        }

        $this->expectException(RuntimeException::class);

        $parsingProcess = new \Thsgroup\FeedParser\ParsingProcess($parameters, $variables, $files);
        $parsingProcess->process();
    }

    private function removeDirectory($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        if (file_exists($path)) {
            rmdir($path);
        }
    }
}
