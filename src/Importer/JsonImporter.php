<?php

namespace App\Importer;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JsonImporter implements ImporterInterface
{

    /**
     * @var FileContentParser
     */
    private $fileContentParser;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(FileContentParser $fileContentParser, EntityManagerInterface $entityManager)
    {

        $this->fileContentParser = $fileContentParser;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \JsonException
     */
    public function import(OutputInterface $output)
    {
        $file = $this->fileContentParser->getParsedFileContent();
        foreach ($file['data'] as $record) {
            if (!$this->validateData($record, $output)) {
                continue;
            }
            $existingRestaurant = $this->entityManager->getRepository(Restaurant::class)->findOneBy(['name' => $record['restaurantName']]);
            if (!$existingRestaurant) {
                $restaurant = new Restaurant();
                $restaurant->setName($record['restaurantName']);
                $restaurant->setCuisine($record['cuisine']);
                $restaurant->setCity($record['city']);
                $restaurant->setLatitude((float)$record['latitude']);
                $restaurant->setLongitude((float)$record['longitude']);
                $this->entityManager->persist($restaurant);
                $output->writeln(sprintf('Restaurant %s added', $restaurant->getName()));
            } else {
                $output->writeln('Reaturant %s exist', $existingRestaurant->getName());
            };
        }
        $this->entityManager->flush();
    }

    /**
     * @param array $record
     * @param OutputInterface $output
     * @return bool
     */
    private function validateData(array $record, OutputInterface $output): bool
    {

        if (!array_key_exists("restaurantName", $record)) {
            $output->writeln("restaurantName is not in dataset. Skipping");
            return false;
        }

        if (!array_key_exists("cuisine", $record)) {
            $output->writeln("cuisine is not in dataset. Skipping");
            return false;
        }

        if (!array_key_exists("city", $record)) {
            $output->writeln("city is not in dataset. Skipping");
            return false;
        }

        if (!array_key_exists("latitude", $record)) {
            $output->writeln("latitude is not in dataset. Skipping");
            return false;
        }

        if (!array_key_exists("longitude", $record)) {
            $output->writeln("longitude is not in dataset. Skipping");
            return false;
        }

        return true;
    }
}