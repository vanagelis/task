<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tax;

use App\Cache\TaxCache;
use App\Dto\TaxResponseDto;
use App\ExternalService\SeriousTax\SeriousTaxService;
use App\Tax\SeriousTaxAdapter;
use PHPUnit\Framework\TestCase;

class SeriousTaxAdapterTest extends TestCase
{
    public function testGetTaxData()
    {
        $service = new SeriousTaxService();
        $taxCache = new TaxCache();

        $adapter = new SeriousTaxAdapter($service, $taxCache);

        $result = $adapter->getTaxData('LV');

        $this->assertInstanceOf(TaxResponseDto::class, $result);

        $this->assertContains(['taxType' => 'VAT', 'percentage' => 22], $result->taxes);
    }
}
