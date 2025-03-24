<?php declare(strict_types=1);

namespace Fondly\Money\Directory;

use Fondly\Money\Enum\HttpMethod;
use Fondly\Money\Money;

final class Utilities
{
    private Money $money;

    public function __construct(Money $money)
    {
        $this->money = $money;
    }

    public function getSchema(): array
    {
        return $this->money->query(HttpMethod::Get, uri: '/schema');
    }
}
