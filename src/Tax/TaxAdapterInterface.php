<?php

declare(strict_types=1);

namespace App\Tax;

use App\Dto\TaxResponseDto;

interface TaxAdapterInterface
{
    public function getTaxData(string $country, ?string $state): TaxResponseDto;
}
