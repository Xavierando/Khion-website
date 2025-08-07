<?php

namespace App\Enums;

enum OrderStatus
{
    case pending;
    case unpaid;
    case paid;
    case production;
    case expedited;
}
