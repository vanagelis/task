<?php

declare(strict_types=1);

namespace App\Dto;

class TaxResponseDto
{
    public function __construct(
        public readonly array $taxes,
    ) {
    }

    public static function create(array $taxes): self {
        return new self($taxes);
    }
}
