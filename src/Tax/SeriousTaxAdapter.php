<?php

declare(strict_types=1);

namespace App\Tax;

use App\Cache\TaxCache;
use App\Dto\TaxResponseDto;
use App\ExternalService\SeriousTax\Location;
use App\ExternalService\SeriousTax\SeriousTaxService;

class SeriousTaxAdapter implements TaxAdapterInterface
{
    public function __construct(
        private readonly SeriousTaxService $service,
        private readonly TaxCache $taxCache,
    ) {
    }

    public function getTaxData(string $country, ?string $state = null): TaxResponseDto
    {
        $location = new Location($country, $state);

        if ($this->taxCache->get($country) !== null) {
            $taxValue = (int) $this->taxCache->get($country);
        } else {
            $taxValue = $this->service->getTaxesResult($location);
            $this->taxCache->set($country, (string) $taxValue);
        }

        return TaxResponseDto::create([
            ['taxType' => 'VAT', 'percentage' => $taxValue]
        ]);
    }
}
