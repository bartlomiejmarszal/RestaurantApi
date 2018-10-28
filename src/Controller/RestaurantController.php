<?php
namespace App\Controller;

use App\Service\Geolocalization;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends Controller
{
    /**
     * @var Geolocalization
     */
    private $geolocalization;

    public function __construct(Geolocalization $geolocalization)
    {
        $this->geolocalization = $geolocalization;
    }

    /**
     * @Route("/restaurant", name="restaurant_list", methods={"GET"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function restaurantList(Request $request)
    {
        $restaurants = $this->getDoctrine()->getRepository("App:Restaurant")->applyRequestParametersFilter($request);
        $restaurants = new ArrayCollection($restaurants);
        $restaurants = $this->geolocalization->applyRequestQuery($request, $restaurants);
        return $this->json($restaurants->toArray(), JsonResponse::HTTP_OK, [], ['groups' => ['display']]);
    }
}