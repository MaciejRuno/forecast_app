<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ForecastService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForecastController extends AbstractController
{
    /**
     * @Route("/forecast", name="forecast")
     * @param Request $request
     * @param ForecastService $forecastService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, ForecastService $forecastService)
    {
        $city = $request->get('city');
        $country = $request->get('country');
        $clientIp = $request->getClientIp();

        $requestData = $forecastService->getRequestData(
            $city, 
            $country, 
            $clientIp
        );

        $forecast = $forecastService->getForecastFromRequestData($requestData);

        return $this->render('forecast/index.html.twig', $forecast);
    }
}
