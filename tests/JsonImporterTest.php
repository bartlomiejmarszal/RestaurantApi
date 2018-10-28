<?php

namespace App\Test\Importer;

use App\Importer\JsonImporter;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Mockery\Mock;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use App\Importer\FileContentParser;
use Symfony\Component\Console\Output\OutputInterface;

class JsonImporterTest extends TestCase
{

    /**
     * @var Mock|FileContentParser
     */
    private $fileContentParserMock;

    /**
     * @var Mock|EntityManagerInterface
     */
    private $entityManagerMock;
    /**
     * @var Mock|OutputInterface
     */
    private $outputInterfaceMock;

    /**
     * @var Mock|ObjectRepository
     */
    private $repoMock;

    protected function setUp()
    {
        $this->fileContentParserMock = \Mockery::mock(FileContentParser::class);
        $this->entityManagerMock = \Mockery::mock(EntityManagerInterface::class);
        $this->outputInterfaceMock = \Mockery::mock(OutputInterface::class);
        $this->repoMock = \Mockery::mock(ObjectRepository::class);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testImport()
    {
        //Prepare
        $importer = new JsonImporter($this->fileContentParserMock, $this->entityManagerMock);
        $this->fileContentParserMock->shouldReceive('getParsedFileContent')->andReturn([
            'data' => [
                [
                    "clientKey" => "qwerty123",
                    "restaurantName" => "some name",
                    "cuisine" => "some cuisine",
                    "city" => "some city",
                    "latitude" => "99.004339",
                    "longitude" => "63.654387"
                ],
                [
                    "clientKey" => "ytrewq4321",
                    "restaurantName" => "some other name",
                    "cuisine" => "some other cousine",
                    "city" => "Malm\u00f6",
                    "latitude" => "11.522091",
                    "longitude" => "11.227970"
                ]
            ]
        ]);

        $this->outputInterfaceMock->shouldReceive('writeln');
        $this->entityManagerMock->shouldReceive('getRepository')->andReturn($this->repoMock);
        $this->repoMock->shouldReceive('findOneBy')->andReturn(null);
        $this->entityManagerMock->shouldReceive('persist')->times(2);
        $this->entityManagerMock->shouldReceive('flush')->once();

        $importer->import($this->outputInterfaceMock);
    }
}

