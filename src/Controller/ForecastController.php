<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\ForecastService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

        if (!$city || !$country) {
            $this->addFlash('error', 'Please provide a City and a country');
            return $this->redirectToRoute('index');
        }

        $logger->info("getting data for $city, $country");

        try {
            $requestData = $forecastService->getRequestData(
                $city, 
                $country, 
                $clientIp
            );
        } catch (\Exception $e) {
            $logger->error($e);
            $this->addFlash('error', 'Error encountered, try again later');
            return $this->redirectToRoute('index');
        }

        $apisResponded = \count($requestData->getApiData());
        $logger->info("got responses from $apisResponded apis");

        try {
            $forecast = $forecastService->getForecastFromRequestData($requestData);
        } catch (\Exception $e) {
            $logger->warning($e);
            $this->addFlash('error', "Not enough data found for $city, $country");
            return $this->redirectToRoute('index');
        }

        return $this->render('forecast/index.html.twig', $forecast);
    }
}
