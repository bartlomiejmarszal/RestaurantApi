<?php

namespace App\Test\Importer;

use PHPUnit\Framework\TestCase;

class FileContentParser extends \App\Importer\FileContentParser
{
    protected function getFileContent(): string
    {
        return <<<EOD
        {
    "clientKey": "qwerty123",
    "restaurantName": "some name",
    "cuisine": "some cuisine",
    "city": "some city",
    "latitude": "99.004339",
    "longitude": "63.654387"
},
{
    "clientKey": "ytrewq4321",
    "restaurantName": "some other name",
    "cuisine": "some other cousine",
    "city": "Malm\u00f6",
    "latitude": "11.522091",
    "longitude": "11.227970"
}
EOD;
    }
}

class FileContentParserTest extends TestCase
{
    public function testGetFileContent()
    {
        $parser = new FileContentParser();

        $result = $parser->getParsedFileContent();

        $this->assertTrue(is_array($result));
        $this->assertEquals(2, count($result['data']));
    }
}
