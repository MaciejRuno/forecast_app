<?php
declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use App\Service\ForecastService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WeatherApiPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has(ForecastService::class)) {
            return;
        }

        $definition = $container->findDefinition(ForecastService::class);

        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('forecast.weatherApiClient');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the ForecastService service
            $definition->addMethodCall('addWeatherApi', [new Reference($id)]);
        }
    }
}
