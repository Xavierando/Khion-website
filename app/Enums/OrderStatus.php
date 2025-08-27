<?php

namespace App\Enums;

use JsonSerializable;

enum OrderStatus: string implements JsonSerializable
{
    case pending = 'pending';
    case paid = 'paid';
    case production = 'production';
    case expedited = 'expedited';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
