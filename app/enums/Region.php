<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Region: string implements HasLabel
{
    case US = 'US';
    case EU = "EU";
    case AU = "AU";
    case India = 'India';
    case Online = "Online";

    public function getLabel():?string
    {
        return $this->value;
    }
}
