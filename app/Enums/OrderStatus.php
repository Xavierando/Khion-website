<?php

namespace App\Enums;

use JsonSerializable;

enum OrderStatus: string implements JsonSerializable
{
    case Pending = 'Pending';
    case pending = 'pending';
    case unpaid = 'unpaid';
    case paid = 'paid';
    case production = 'production';
    case expedited = 'expedited';

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
