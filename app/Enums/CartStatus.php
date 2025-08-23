<?php

namespace App\Enums;

use JsonSerializable;

enum CartStatus: string implements JsonSerializable
{
    case pending = 'pending';
    case ordered = 'ordered';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
