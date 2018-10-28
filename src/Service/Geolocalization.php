<?php

namespace App\Service;


use App\Entity\Restaurant;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;

class Geolocalization
{
    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula [Km].
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @return float
     */
    public function getDistance(float $lat1, float $lon1, float $lat2, float $lon2)
    {
        $earthRadius = 6371;
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $latDelta = $lat2 - $lat1;
        $lonDelta = $lon2 - $lon1;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($lat1) * cos($lat2) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }


    /**
     * @param Request $request
     * @param ArrayCollection $restaurants
     * @return array
     */
    public function applyRequestQuery(Request $request, ArrayCollection $restaurants): ArrayCollection
    {
        $location = $request->query->get('location');
        if ($location) {
            $location = explode(',', $location);
            $lat = (float)$location[0];
            $lon = (float)$location[1];
            $desiredDistance = (int)$location[2];
            $restaurants->map(function (Restaurant $restaurant) use ($lat, $lon){
                $restaurant->setDistance($this->getDistance($lat, $lon, $restaurant->getLatitude(), $restaurant->getLongitude()));
            });
            $restaurants = $restaurants->filter(function (Restaurant $restaurant) use ($desiredDistance) {
               return $restaurant->getDistance() <= $desiredDistance;
            });
            $iterator = $restaurants->getIterator();
            $iterator->uasort(function ($a, $b) {
                return ($a->getDistance() < $b->getDistance()) ? -1 : 1;
            });
            $restaurants = new ArrayCollection(iterator_to_array($iterator));
        }

        return $restaurants;
    }
}