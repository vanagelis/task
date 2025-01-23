<?php

declare(strict_types=1);

namespace App\Service;

use App\Cache\TaxCache;
use App\Enum\CountryEnum;
use App\Exception\UnsupportedCountryException;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\ExternalService\TaxBee\TaxBee;
use App\Tax\SeriousTaxAdapter;
use App\Tax\TaxAdapterInterface;
use App\Tax\TaxBeeAdapter;

class TaxService
{
    /**
     * @throws UnsupportedCountryException
     */
    public function getTaxesAdapter(string $country, ?string $state): TaxAdapterInterface
    {
        $taxCache = new TaxCache();
        if ($state !== null) {
            $taxBee = new TaxBee();
            $adapter = new TaxBeeAdapter($taxBee, $taxCache);
        } else {
            $seriousTax = new SeriousTaxService();
            $adapter = new SeriousTaxAdapter($seriousTax, $taxCache);
        }
        if (!CountryEnum::tryFrom($country)) {
            throw new UnsupportedCountryException('Country not supported');
        }

        return $adapter;
    }
}
