<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ForecastService;
use Psr\Log\LoggerInterface;
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
    public function index
    (
        Request $request, 
        ForecastService $forecastService,
        LoggerInterface $logger
    )
    {
        $city = $request->get('city');
        $country = $request->get('country');
        $clientIp = $request->getClientIp();

        $logger->info("getting data for $city, $country");

        $requestData = $forecastService->getRequestData(
            $city, 
            $country, 
            $clientIp
        );

        $apisResponded = \count($requestData->getApiData());
        $logger->info("got responses from $apisResponded apis");

        $forecast = $forecastService->getForecastFromRequestData($requestData);

        return $this->render('forecast/index.html.twig', $forecast);
    }
}
