<?php

declare(strict_types=1);

namespace App\Tax;

use App\Cache\TaxCache;
use App\Dto\TaxResponseDto;
use App\ExternalService\TaxBee\TaxBee;

class TaxBeeAdapter implements TaxAdapterInterface
{
    public function __construct(
        private readonly TaxBee $taxBee,
        private readonly TaxCache $taxCache,
    ) {
    }

    public function getTaxData(string $country, ?string $state): TaxResponseDto
    {
        $state = mb_strtolower($state);

        if ($this->taxCache->get($country . '-' . $state) !== null) {
            $taxResult = $this->taxCache->get($country . '-' . $state);
            $taxResult = json_decode($taxResult);
        } else {
            $taxResult = $this->taxBee->getTaxes($country, $state, '', '', '');
            $this->taxCache->set($country . '-' . $state, json_encode($taxResult));
        }

        $data = [];

        foreach ($taxResult as $tax) {
            $data[] = ['taxType' => $tax->type, 'percentage' => $tax->taxPercentage];
        }

        return TaxResponseDto::create($data);
    }
}
