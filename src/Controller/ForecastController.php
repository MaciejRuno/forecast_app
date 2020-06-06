<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ForecastController extends AbstractController
{
    /**
     * @Route("/forecast", name="forecast")
     */
    public function index()
    {
        return $this->render('forecast/index.html.twig', [
            'temp' => 26,
            'wind' => 16,
            'humidity' => 16,
            'wind' => 16,
        ]);
    }
}
