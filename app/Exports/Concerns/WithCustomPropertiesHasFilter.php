<?php

namespace App\Exports\Concerns;

interface WithCustomPropertiesHasFilter
{
    public function title(): string;

    public function dataFilter(): array;
}
