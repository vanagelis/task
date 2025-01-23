<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Exception\UnsupportedCountryException;
use App\Service\TaxService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaxesControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testGetTaxesWithoutCountry()
    {
        $this->client->request('GET', '/taxes', ['state' => 'SomeState']);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Country are required.']),
            $this->client->getResponse()->getContent()
        );
    }

    public function testGetTaxesWithUnsupportedCountry()
    {
        $taxService = $this->createMock(TaxService::class);
        $taxService->method('getTaxesAdapter')->willThrowException(new UnsupportedCountryException('Country not supported'));

        self::getContainer()->set(TaxService::class, $taxService);

        $this->client->request('GET', '/taxes', ['country' => 'UnsupportedCountry']);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Country not supported']),
            $this->client->getResponse()->getContent()
        );
    }
}
