<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\UnsupportedCountryException;
use App\ExternalService\SeriousTax\TimeoutException;
use App\Service\TaxService;
use App\Tax\TaxCalculatorContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

readonly class TaxesController
{
    public function __construct(
        public TaxCalculatorContext $taxCalculatorContext,
        public TaxService $taxService,
    ) {
    }

    #[Route('/taxes')]
    public function getTaxes(Request $request): Response
    {
        $country = $request->get('country');
        $state = $request->get('state');

        if ($country === null) {
            return new JsonResponse(['error' => 'Country are required.'], Response::HTTP_BAD_REQUEST);
        }
        try {
            $taxesAdapter = $this->taxService->getTaxesAdapter($country, $state);
        } catch (UnsupportedCountryException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        $this->taxCalculatorContext->setAdapter($taxesAdapter);
        try {
            $response = $this->taxCalculatorContext->getTaxData($country, $state);
        } catch (TimeoutException $e) {
            return new JsonResponse(['error' => 'could not retrieve'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($response->taxes);
    }
}
