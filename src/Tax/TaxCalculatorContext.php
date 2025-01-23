<?php

declare(strict_types=1);

namespace App\Tax;

use App\Dto\TaxResponseDto;

class TaxCalculatorContext
{
    private TaxAdapterInterface $adapter;

    public function setAdapter($adapter): void
    {
        $this->adapter = $adapter;
    }

    public function getTaxData(string $country, ?string $state): TaxResponseDto
    {
        return $this->adapter->getTaxData($country, $state);
    }
}
