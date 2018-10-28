<?php

namespace App\Tests;

use App\Entity\Restaurant;
use App\Service\Geolocalization;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class GeolocalizagionTest extends TestCase
{

    /**
     * @test
     */
    public function testGetDistance()
    {
        $lat1 = 41.8350;
        $lon1 = 12.470;
        $lat2 = 41.9133741000;
        $lon2 = 12.5203944000;


        $gelocalization = new Geolocalization();
        $distance = $gelocalization->getDistance($lat1, $lon1, $lat2, $lon2);
        $this->assertEquals(9.662174538188, $distance);
    }

    protected function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }

    public function testApplyRequestQuery()
    {

        $restaurant1 = new Restaurant();
        $restaurant1->setName('Restaurant 1');
        $restaurant1->setCuisine('cousine');
        $restaurant1->setCity('City');
        $restaurant1->setLongitude(18.012011);
        $restaurant1->setLatitude(59.336018);
        $restaurant2 = new Restaurant();
        $restaurant2->setName('Restaurant 2');
        $restaurant2->setCuisine('cousine 2');
        $restaurant2->setCity('City 2');
        $restaurant2->setLongitude(12.995891);
        $restaurant2->setLatitude(55.553375);
        $requestMock = \Mockery::mock(Request::class);
        $paramBagMock = \Mockery::mock(ParameterBag::class);
        $requestMock->query = $paramBagMock;
        $paramBagMock->shouldReceive('get')->with('location')->andReturn('59.316042, 18.082027,5');
        $restaurantsMock = new ArrayCollection();
        $restaurantsMock->add($restaurant1);
        $restaurantsMock->add($restaurant2);


        $gelocalization = new Geolocalization();
        $result = $gelocalization->applyRequestQuery($requestMock, $restaurantsMock);

        $this->assertEquals(1, count($result));
    }
}
